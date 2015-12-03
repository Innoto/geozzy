<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyResourceView.php' );



class AdminViewResource extends AdminViewMaster {

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
    Section list user
   **/
  public function listResources() {

    $template = new Template( $this->baseDir );
    $template->assign('resourceTable', table::getTableHtml('AdminViewResource', '/admin/resource/table') );
    $template->setTpl('listResource.tpl', 'admin');

    $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems()->fetchAll();

    $resCreateByType = '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
    foreach( $resourcetypelist as $i => $res ) {
      $typeList[ $i ] = $res->getter('name_es');
      $resCreateByType .= '<li><a class="create-'.$res->getter('idName').'" href="/admin#resource/create/resourcetype/'.$res->getter('id').'">'.$res->getter('name_es').'</a></li>';
    }
    $resCreateByType .= '</ul>';

    $this->template->addToBlock( 'col12', $template );
    $this->template->assign( 'headTitle', __('Resource Management') );
    $this->template->assign( 'headActions', '<div class="btn-group assignResource AdminViewResource">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>'
    );
    $this->template->assign( 'footerActions', '<div class="btn-group assignResource AdminViewResource">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>' );
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
    $tabla->setEachRowUrl('"/admin#resource/edit/id/".$rowId');
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

    $typeModel =  new ResourcetypeModel();
    $typeList = $typeModel->listItems()->fetchAll();
    foreach( $typeList as $id => $type ) {
      $tabla->colRule('rTypeId', '#'.$id.'#', $type->getter('name'));
    }

    // imprimimos o JSON da taboa
    $tabla->exec();
  }


  /**
   * Creacion de Recursos
   */
  public function resourceForm( $urlParams = false ) {
    $recursoData = false;


    /* Validamos os parámetros da url e obtemos un array de volta*/
    $validation = array( 'topic'=> '#^\d+$#', 'resourcetype' => '#^\d+$#', 'star' => '#^\d+$#' );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    if( $urlParamsList ) {
      $topicItem = false;
      $rTypeItem = false;

      if( isset( $urlParamsList['topic'] ) ) {
        $urlParamTopic = $urlParamsList['topic'];
        $topicControl = new TopicModel();
        $topicItem = $topicControl->ListItems( array( 'filters' => array( 'id' => $urlParamTopic ) ) )->fetch();
      }

      if( isset( $urlParamsList['resourcetype'] ) ) {
        $urlParamRtype = $urlParamsList['resourcetype'];
        $rtypeControl = new ResourcetypeModel();
        $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'id' => $urlParamRtype ) ) )->fetch();
      }

      if( $rTypeItem ) {
        $recursoData = array();
        $recursoData['rTypeId'] = $rTypeItem->getter('id');
        $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');

        if( $topicItem ) {
          $rtypeTopicControl = new ResourcetypeTopicModel();
          $resourcetypeTopic = $rtypeTopicControl->ListItems(
            array( 'filters' => array( 'topic' => $urlParamTopic, 'resourceType' => $urlParamRtype ) )
          )->fetch();

          if( $resourcetypeTopic ){
            $recursoData['topicReturn'] = $topicItem->getter('id');
            $recursoData['topics'] = $topicItem->getter('id');
          }
        }
      }
    }

    $this->resourceShowForm( 'resourceCreate', '/admin/resource/sendresource', $recursoData );
  } // function resourceForm()


  /**
   * Edicion de Recursos
   */
  public function resourceEditForm( $urlParams = false ) {

    $recursoData = false;
    $urlParamTopic = false;
    $topicItem = false;
    $typeItem = false;

    /* Validamos os parámetros da url e obtemos un array de volta*/
    $validation = array( 'topic'=> '#^\d+$#', 'resourceId'=> '#^\d+$#', 'type'=> '#^\d+$#' );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    $resCtrl = new ResourceController();
    $recursoData = $resCtrl->getResourceData( $urlParamsList['resourceId'] );

    if( isset( $urlParamsList['topic'] ) ) {
      $urlParamTopic = $urlParamsList['topic'];
      $topicControl = new TopicModel();
      $topicItem = $topicControl->ListItems( array( 'filters' => array( 'id' => $urlParamTopic ) ) )->fetch();
    }

    if (isset( $urlParamsList['type'])){
      $typeItem = $urlParamsList['type'];
    }

    if( $topicItem ) {
      $rtypeTopicControl = new ResourcetypeTopicModel();
      $resourcetypeTopic = $rtypeTopicControl->ListItems(
        array( 'filters' => array( 'topic' => $urlParamTopic, 'resourceType' => $recursoData['rTypeId'] ) )
      )->fetch();

      if( $resourcetypeTopic ){
        $recursoData['topicReturn'] = $topicItem->getter('id');
      }
    }
    else{
      if ($typeItem){
        $recursoData['typeReturn'] = $typeItem;
      }
    }

    if( $recursoData ) {
      $this->resourceShowForm( 'resourceEdit', '/admin/resource/sendresource', $recursoData, $resCtrl );
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function resourceEditForm()



  public function resourceShowForm( $formName, $urlAction, $valuesArray = false, $resCtrl = false ) {

    if( !$resCtrl ) {
      $resCtrl = new ResourceController();
    }


    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, $valuesArray );
    $formBlockInfo['template']['adminFull']->exec();
  }


  public function resourceShowForm_PRE( $formName, $formUrl, $recursoData = false, $resCtrl = false ) {

    if( !$resCtrl ) {
      $resCtrl = new ResourceController();
    }

    // error_log( 'recursoData para FORM: ' . print_r( $recursoData, true ) );
    $formBlock = $resCtrl->getFormBlock( $formName, $formUrl, $recursoData );

    // Cambiamos el template del formulario
    $formBlock->setTpl( 'resourceFormBlockBase.tpl', 'admin' );

    // Template base: Admin 8-4
    $this->template->assign( 'headTitle', __('Edit Resource') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );


    $resCtrl->loadAdminFormColumns( $formBlock, $this->template, $this );

    $this->template->exec();
  }


  /**
   * Creacion de Recursos type URL
   */
  public function resourceTypeUrlForm( $urlParams = false ) {


  $resCtrl = new ResourceController();
  $rtypeModel = new ResourcetypeModel();

  $formName = 'resourceUrlCreate';
  $urlAction = '/admin/resourcetypeurl/sendresource';

  $rtype = $rtypeModel->listItems( array( 'filters' => array('idName' => 'rtypeUrl') ) )->fetch();
  $valuesArray['rTypeId'] = $rtype->getter('id');

  $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, $valuesArray );
  $formBlockInfo['template']['miniFormModal']->exec();
  }


  /**
   * Creacion de Recursos type FILE
   */
  public function resourceTypeFileForm( $urlParams = false ) {

    $resCtrl = new ResourceController();
    $rtypeModel = new ResourcetypeModel();

    $formName = 'resourceFileCreate';
    $urlAction = '/admin/resourcetypefile/sendresource';

    $rtype = $rtypeModel->listItems( array( 'filters' => array('idName' => 'rtypeFile') ) )->fetch();
    $valuesArray['rTypeId'] = $rtype->getter('id');

    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, $valuesArray );
    $formBlockInfo['template']['miniFormModal']->exec();
  }




  public function sendResourceForm() {
    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  }

  public function sendModalResourceForm() {
    $resourceView = new GeozzyResourceView();
    $resource = null;

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $resourceView->defResCtrl->resFormLoad();

    if( !$form->existErrors() ) {
      // Validar y guardar los datos
      $resource = $resourceView->actionResourceFormProcess( $form );
    }

    if( !$form->existErrors() ) {
      $form->removeSuccess( 'redirect' );
      $form->setSuccess( 'jsEval', ' successResourceForm( { '.
        ' id : "'.$resource->getter('id').'",'.
        ' title: "'.$resource->getter('title_'.$form->langDefault).'",'.
        ' image: "'.$resource->getter('image').'" });'
      );
    }

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $resourceView->actionResourceFormSuccess( $form, $resource );
  }

}
