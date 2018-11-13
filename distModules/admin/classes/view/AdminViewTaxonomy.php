<?php
admin::load('view/AdminViewMaster.php');


class AdminViewTaxonomy extends AdminViewMaster {

  public function __construct( $base_dir ) {
    parent::__construct($base_dir);

  }


  public function categoryTermsSync( $request ) {

    $id = mb_substr($request[1], 1);

    header('Content-type: application/json');
    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'PUT':
        $putData = json_decode(file_get_contents('php://input'), true);

        if( is_numeric( $id )) {  // update
          $taxtermModel = new TaxonomytermModel();
          $taxTerm = $taxtermModel->listItems(  array( 'filters' => array( 'id'=>$id ) ))->fetch();
          $taxTerm->setter('name', $putData['name']);
          $taxTerm->save();
        }
        else { // create
          $taxTerm = new TaxonomytermModel( array('name'=> $putData['name'], 'parent'=> null, 'taxgroup'=> $putData['taxgroup']) );
          $taxTerm->save();
        }

        $termData = $taxTerm->getAllData();
        echo json_encode( $termData['data'] );

        break;
      case 'GET':
        $taxtermModel = new TaxonomyViewModel();
        $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$_GET['group']) ) );
        echo '[';
        $c = '';
        while( $taxTerm = $taxtermList->fetch() ) {
          $termData = $taxTerm->getAllData();
          echo $c.json_encode( $termData['data'] );
          if( $c === '' ) {
            $c=',';
          }
        }
        echo ']';

        break;
      case 'DELETE':
        $taxTerm = new TaxonomytermModel( array( 'id'=> $id ) );
        $taxTerm->delete();

        break;
    }
  }

  public function categoriesSync() {
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));

    header('Content-type: application/json');
    echo '[';
    $c = '';
    while ($taxGroup = $taxGroupList->fetch() ) {
      $taxData = $taxGroup->getAllData();
      echo $c.json_encode( $taxData['data'] );
      if( $c === '' ) {
        $c=',';
      }
    }
    echo ']';
  }

  public function categoryForm( $request ){
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions( array('category:edit', 'category:create'), 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $geozzyTaxtermView = new GeozzyTaxonomytermView();

    $catId = $request[1];

    $form = $geozzyTaxtermView->taxtermFormDefine( $request );
    $form->setAction('/admin/category/term/sendcategoryterm');
    $form->setSuccess( 'redirect', '/admin#category/'.$catId );

    $formBlock = $geozzyTaxtermView->taxtermGetFormBlock( $form );

    $taxtermFormFieldsArray = $formBlock->getTemplateVars( 'taxtermFormFieldsArray' );
    $formSeparate[ 'icon' ] = $taxtermFormFieldsArray[ 'icon' ];
    unset( $taxtermFormFieldsArray[ 'icon' ] );
    $formBlock->assign( 'taxtermFormFieldsArray', $taxtermFormFieldsArray );

    $panel = $this->getPanelBlock( $formBlock, 'Category form', 'fa-tag' );

    $this->template->addToFragment( 'col8', $panel );

    $this->template->addToFragment( 'col4', $this->getPanelBlock( $formSeparate[ 'icon' ], __( 'Selecciona un icono' ) ) );

    $this->template->assign( 'headTitle', __('Category form') );
    $this->template->assign( 'headActions', '<a href="/admin#category/'.$catId.'" class="btn btn-danger"> '.__('Cancel').'</a>' );
    $this->template->assign( 'footerActions', '<a href="/admin#category/'.$catId.'" class="btn btn-danger"> '.__('Cancel').'</a>' );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $this->template->exec();
  }

  public function menuForm( $request = false ){
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions( array('category:edit', 'category:create'), 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $geozzyTaxtermView = new GeozzyTaxonomytermView();

    $taxgroupModel = new TaxonomygroupModel();
    $taxgroupElement = $taxgroupModel->listItems(array( 'filters' => array( 'idName' => 'menu' ) ))->fetch();

    if( !empty($request) ){
      $request[2] = $request[1];
    }
    $request[1] = "".$taxgroupElement->getter('id');


    $rsViewModel = new ResourceViewModel();

    $filter['inRtypeIdName'] = !empty(Cogumelo::getSetupValue( 'mod:admin:menuRTypes' )) ? Cogumelo::getSetupValue( 'mod:admin:menuRTypes' ) : [];
    $rsMenuObj = $rsViewModel->listItems([
      'filters' => $filter,
      'cache' => true
    ]);
    $optionsResMenu[""] = __('None');
    if( is_object( $rsMenuObj ) ) {
      while( $res = $rsMenuObj->fetch() ){
        if( $res ){
          $optionsResMenu[ $res->getter('id') ] = $res->getter( 'title', Cogumelo::getSetupValue( 'lang:default' ));
        }
      }
    }

    $valueResMenu = false;

    if(!empty($request[2])){
      $resTaxTermModel = new ResourceTaxonomytermModel();
      $resTaxTermObj = $resTaxTermModel->listItems( ['filters' => [ 'taxonomyterm' => $request[2] ] ]);
      if(is_object($resTaxTermObj)){
        $resTermItem = $resTaxTermObj->fetch();
        if( $resTermItem ) {
          $valueResMenu = $resTermItem->getter('resource');
        }
      }
    }

    $form = $geozzyTaxtermView->taxtermFormDefine( $request );
    $form->setField( 'relTermMenuRes',
      array(
        'type' => 'select',
        'label' => __( 'Related resource' ),
        'class' => 'gzzSelect2',
        'options'=> $optionsResMenu,
        'value' => $valueResMenu
      )
    );

    $form->setAction('/admin/menu/term/sendmenuterm');
    $form->setSuccess( 'redirect', '/admin#menu');

    $formBlock = $geozzyTaxtermView->taxtermGetFormBlock( $form );
    $taxtermFormFieldsArray = $formBlock->getTemplateVars( 'taxtermFormFieldsArray' );
    $formSeparate[ 'icon' ] = $taxtermFormFieldsArray[ 'icon' ];
    $formSeparate[ 'relTermMenuRes' ] = $taxtermFormFieldsArray[ 'relTermMenuRes' ];
    unset( $taxtermFormFieldsArray[ 'icon' ] );
    unset( $taxtermFormFieldsArray[ 'relTermMenuRes' ] );
    $formBlock->assign( 'taxtermFormFieldsArray', $taxtermFormFieldsArray );
    $panel = $this->getPanelBlock( $formBlock, 'Menu form', 'fa-tag' );
    $this->template->addToFragment( 'col8', $panel );

    $this->template->addToFragment( 'col4', $this->getPanelBlock( $formSeparate[ 'icon' ], __( 'Selecciona un icono' ) ) );
    $this->template->addToFragment( 'col4', $this->getPanelBlock( $formSeparate[ 'relTermMenuRes' ], __( 'Related resource' ) ) );

    $this->template->assign( 'headTitle', __('Menu form') );
    $this->template->assign( 'headActions', '<a href="/admin#menu" class="btn btn-danger"> '.__('Cancel').'</a>' );
    $this->template->assign( 'footerActions', '<a href="/admin#menu" class="btn btn-danger"> '.__('Cancel').'</a>' );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $this->template->exec();
  }

  public function sendCategoryForm(){
    $geozzyTaxtermView = new GeozzyTaxonomytermView();
    $geozzyTaxtermView->sendTaxtermForm();
  }
}
