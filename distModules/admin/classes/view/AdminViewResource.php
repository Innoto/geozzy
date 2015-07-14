<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyResourceView.php' );

class AdminViewResource extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list user
  **/
  public function listResources() {

    $template = new Template( $this->baseDir );
    $template->assign('resourceTable', table::getTableHtml('AdminViewResource', '/admin/resource/table') );
    $template->setTpl('listResource.tpl', 'admin');

    $this->template->addToBlock( 'col12', $template );
    $this->template->assign( 'headTitle', __('Resource Management') );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }


  public function listResourcesTable() {

    table::autoIncludes();
    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    $tabla->setTabs(__('published'), array('1'=>__('Published'), '0'=>__('Unpublished'), '*'=> __('All') ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set table Actions
    $tabla->setActionMethod(__('Publish'), 'changeStatusPublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>1 ))');
    $tabla->setActionMethod(__('Unpublish'), 'changeStatusUnpublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>0 ))');
    $tabla->setActionMethod(__('Delete'), 'delete', 'listitems(array("filters" => array("id" => $rowId)))->fetch()->delete()');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#resource/edit/".$rowId');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'ID');
    $tabla->setCol('rTypeId', __('Type'));
    $tabla->setCol('title_'.LANG_DEFAULT, __('Title'));
    $tabla->setCol('published', __('Published'));

    // Filtrar por temática
    /*
    $tabla->setDefaultFilters( array('ResourceTopicModel.topic'=> 15 ) );
    $tabla->setAffectsDependences( array('ResourceTopicModel') ) ;
    $tabla->setJoinType('INNER');
    */

    // Contido especial
    $tabla->colRule('published', '#1#', '<span class=\"rowMark rowOk\"><i class=\"fa fa-circle\"></i></span>');
    $tabla->colRule('published', '#0#', '<span class=\"rowMark rowNo\"><i class=\"fa fa-circle\"></i></span>');

    // imprimimos o JSON da taboa
    $tabla->exec();
  }


  /**
    Creacion/Edicion de Recursos
   */

  public function resourceForm( $urlParams = false ) {
    $formName = 'resourceCreate';
    $formUrl = '/admin/resource/sendresource';

    $resourceView = new GeozzyResourceView();

    if( $urlParams ) {
      $recursoData['topics'] = array( $urlParams['1'] );
      $recursoData['rTypeId'] = $urlParams['2'];
      $formBlock = $resourceView->getFormBlock( $formName, $formUrl, $recursoData );
    }
    else{
      $formBlock = $resourceView->getFormBlock( $formName, $formUrl, false );
    }

    // Cambiamos el template del formulario
    $formBlock->setTpl( 'resourceFormBlockBase.tpl', 'admin' );

    // Template base: Admin 8-4
    $this->template->assign( 'headTitle', __('Create Resource') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $this->showFormBlocks( $formBlock );
  } // function resourceForm()


  public function resourceEditForm( $urlParams = false ) {
    $formName = 'resourceCreate';
    $formUrl = '/admin/resource/sendresource';

    $valuesArray = false;

    if( isset( $urlParams['1'] ) ) {
      $resCtrl = new ResourceController();
      $valuesArray = $resCtrl->getResourceData( $urlParams['1'] );
    }

    if( $valuesArray ) {
      $resourceView = new GeozzyResourceView();

      // error_log( 'recursoData para FORM: ' . print_r( $valuesArray, true ) );
      $formBlock = $resourceView->getFormBlock( $formName,  $formUrl, $valuesArray );

      // Cambiamos el template del formulario
      $formBlock->setTpl( 'resourceFormBlockBase.tpl', 'admin' );

      // Template base: Admin 8-4
      $this->template->assign( 'headTitle', __('Edit Resource') );
      $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

      $this->showFormBlocks( $formBlock );
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function resourceEditForm()


  private function showFormBlocks( $formBlock ) {
    // Fragmentamos el formulario generado
    $formImage = $this->extractFormBlockFields( $formBlock, array( 'image' ) );
    $formPublished = $this->extractFormBlockFields( $formBlock, array( 'published' ) );
    $formStatus = $this->extractFormBlockFields( $formBlock, array( 'topics', 'starred' ) );
    $formSeo = $this->extractFormBlockFields( $formBlock,
      array( 'urlAlias', 'headKeywords', 'headDescription', 'headTitle' ) );
    $formContacto = $this->extractFormBlockFields( $formBlock, array( 'datoExtra1', 'datoExtra2' ) );
    $formCollections = $this->extractFormBlockFields( $formBlock, array( 'collections', 'addCollections' ) );


    // El bloque que usa $formBlock contiene la estructura del form

    // Bloques de 8
    $this->template->addToBlock( 'col8', $this->getPanelBlock( $formBlock, __('Edit Resource'), 'fa-archive' ) );
    $this->template->addToBlock( 'col8', $this->getPanelBlock( implode( "\n", $formCollections ), __('Collections of related resources'), 'fa-th-large' ) );
    $this->template->addToBlock( 'col8', $this->getPanelBlock( implode( "\n", $formContacto ), __('Contact'), 'fa-archive' ) );

    // Bloques de 4
    $this->template->addToBlock( 'col4', $this->getPanelBlock( implode( "\n", $formPublished ), __( 'Publication' ), 'fa-adjust' ) );
    $this->template->addToBlock( 'col4', $this->getPanelBlock( implode( "\n", $formImage ), __( 'Select a image' ), 'fa-file-image-o' ) );
    $this->template->addToBlock( 'col4', $this->getPanelBlock( implode( "\n", $formStatus ), __( 'Status' ) ) );
    $this->template->addToBlock( 'col4', $this->getPanelBlock( implode( "\n", $formSeo ), __( 'SEO' ), 'fa-globe' ) );

    $this->template->exec();
  }


  public function sendResourceForm() {
    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  } // sendResourceForm()

}
