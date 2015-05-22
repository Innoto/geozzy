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
    $tabla->setCol('type', __('Type'));
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

  public function resourceForm() {
    error_log( "AdminFormsAPIView: resourceForm()" );

    $formName = 'resourceCreate';
    $formUrl = '/admin/resource/sendresource';

    $resourceView = new GeozzyResourceView();
    $formBlock = $resourceView->getFormBlock( $formName,  $formUrl, false );
    $panel = $this->getPanelBlock( $formBlock, 'New Resource', 'fa-archive' );
    $this->template->addToBlock( 'col8', $panel );


    $panel = $this->getPanelBlock( 'Recuerda que en algunos campos existe versión en varios idiomas.' );
    $this->template->addToBlock( 'col4', $panel );
    $this->template->assign( 'headTitle', __('Create Resource') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $this->template->exec();
  } // function resourceForm()


  public function resourceEditForm( $urlParams = false ) {
    error_log( "AdminFormsAPIView: resourceEditForm()". print_r( $urlParams, true ) );

    $formName = 'resourceCreate';
    $formUrl = '/admin/resource/sendresource';

    $recurso = false;

    if( isset( $urlParams['1'] ) ) {
      $idResource = $urlParams['1'];
      $recModel = new ResourceModel();
      $recursosList = $recModel->listItems( array( 'affectsDependences' => array( 'FiledataModel' ),
        'filters' => array( 'id' => $idResource ) ) );
      /*
      $recursosList = $recModel->listItems( array( 'affectsDependences' => array( 'FiledataModel', 'UrlAliasModel' ),
          'filters' => array( 'id' => $idResource ) ) );
      */
      $recurso = $recursosList->fetch();
    }

    if( $recurso ) {
      $recursoData = $recurso->getAllData();

      error_log( 'recursoData: ' . print_r( $recursoData, true ) );

      error_log( 'recursoData: ' . print_r( $recursoData, true ) );

      $resourceView = new GeozzyResourceView();
      $formBlock = $resourceView->getFormBlock( $formName,  $formUrl, $recursoData[ 'data' ] );
      $panel = $this->getPanelBlock( $formBlock, 'Edit Resource', 'fa-archive' );
      $this->template->addToBlock( 'col8', $panel );

      $html = 'Recurso asociado con:<br>'.
        ' <i class="fa fa-times"></i> Playas<br>'.
        ' <i class="fa fa-times"></i> Lugares<br>'.
        ' <i class="fa fa-times"></i> Fiesta<br>'.
        '<br>'.
        ' <i class="fa fa-times"></i> Desvincular de TODAS<br>'.
        '<br>'.
        ' <i class="fa fa-times"></i> Eliminar Recurso<br>'.
        '';
      $panel = $this->getPanelBlock( $html, 'Information' );
      $this->template->addToBlock( 'col4', $panel );

      $this->template->addToBlock( 'col4', $this->getPanelBlock( 'Esto é un segundo panel' ) );
      $this->template->assign( 'headTitle', __('Edit Resource') );
      $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

      $this->template->exec();
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function resourceEditForm()


  public function sendResourceForm() {
    error_log( "AdminFormsAPIView: sendResourceForm()" );

    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  } // sendResourceForm()

}
