<?php
admin::load('view/AdminViewMaster.php');


class AdminViewTaxonomy extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  function categoryTermsSync( $request ) {


    $id = substr($request[1], 1);

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
        $taxtermModel = new TaxonomytermModel();
        $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$_GET['group']) ) );
        echo '[';
        $c = '';
        while ($taxTerm = $taxtermList->fetch() )
        {
          $termData = $taxTerm->getAllData();
          echo $c.json_encode( $termData['data'] );
          if($c === ''){$c=',';}
        }
        echo ']';

        break;

      case 'DELETE':
        $taxTerm = new TaxonomytermModel( array( 'id'=> $id ) );
        $taxTerm->delete();

        break;
    }



  }

  function categoriesSync() {
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));

    header('Content-type: application/json');

    echo '[';

    $c = '';
    while ($taxGroup = $taxGroupList->fetch() )
    {
      $taxData = $taxGroup->getAllData();
      echo $c.json_encode( $taxData['data'] );
      if($c === ''){$c=',';}
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

  public function sendCategoryForm(){
    $geozzyTaxtermView = new GeozzyTaxonomytermView();
    $geozzyTaxtermView->sendTaxtermForm();
  }



}
