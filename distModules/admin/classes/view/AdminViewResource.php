<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyResourceView.php' );
Cogumelo::load("coreController/RequestController.php");

class AdminViewResource extends AdminViewMaster {

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
    foreach ($typeList as $id => $type){
      $tabla->colRule('rTypeId', '#'.$id.'#', $type->getter('name'));
    }

    // imprimimos o JSON da taboa
    $tabla->exec();
  }


  /**
    Creacion/Edicion de Recursos
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

    $resourceView = new GeozzyResourceView();
    $this->resourceShowForm( 'resourceCreate', '/admin/resource/sendresource', $recursoData );
  }


  public function resourceEditForm( $urlParams = false ) {
    $recursoData = false;

    /* Validamos os parámetros da url e obtemos un array de volta*/
    $validation = array( 'resourceId'=> '#^\d+$#' );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    if( isset( $urlParamsList['resourceId'] ) ) {
      $resCtrl = new ResourceController();
      $recursoData = $resCtrl->getResourceData( $urlParamsList['resourceId'] );
    }

    if( $recursoData ) {
      $resourceView = new GeozzyResourceView();
      $this->resourceShowForm( 'resourceEdit', '/admin/resource/sendresource', $recursoData );
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function resourceEditForm()



  public function resourceShowForm( $formName, $formUrl, $recursoData = false ) {
    $resourceView = new GeozzyResourceView();

    // error_log( 'recursoData para FORM: ' . print_r( $recursoData, true ) );
    $formBlock = $resourceView->getFormBlock( $formName, $formUrl, $recursoData );

    // Cambiamos el template del formulario
    $formBlock->setTpl( 'resourceFormBlockBase.tpl', 'admin' );

    // Template base: Admin 8-4
    $this->template->assign( 'headTitle', __('Edit Resource') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $this->showFormBlocks( $formBlock );
  }


  private function showFormBlocks( $formBlock ) {
    // Fragmentamos el formulario generado
    $formImage = $this->extractFormBlockFields( $formBlock, array( 'image' ) );
    $formPublished = $this->extractFormBlockFields( $formBlock, array( 'published' ) );
    $formStatus = $this->extractFormBlockFields( $formBlock, array( 'topics', 'starred' ) );
    $formSeo = $this->extractFormBlockFields( $formBlock,
      array( 'urlAlias', 'headKeywords', 'headDescription', 'headTitle' ) );
    $formContacto = $this->extractFormBlockFields( $formBlock, array( 'datoExtra1', 'datoExtra2' ) );
    $formCollections = $this->extractFormBlockFields( $formBlock, array( 'collections', 'addCollections' ) );
    $formMultimediaGalleries = $this->extractFormBlockFields( $formBlock, array( 'multimediaGalleries', 'addMultimediaGalleries' ) );
    $formLatLon = $this->extractFormBlockFields( $formBlock, array( 'locLat', 'locLon', 'defaultZoom' ) );


    // El bloque que usa $formBlock contiene la estructura del form

    // Bloques de 8
    $this->template->addToBlock( 'col8', $this->getPanelBlock( $formBlock, __('Edit Resource'), 'fa-archive' ) );
    if( $formLatLon ) {
      $this->template->addToBlock( 'col8', $this->getPanelBlock( implode( "\n", $formLatLon ), __('Location'), 'fa-archive' ) );
    }
    if( $formCollections ) {
      $this->template->addToBlock( 'col8', $this->getPanelBlock( implode( "\n", $formCollections ), __('Collections of related resources'), 'fa-th-large' ) );
    }
    if( $formMultimediaGalleries ) {
      $this->template->addToBlock( 'col8', $this->getPanelBlock( implode( "\n", $formMultimediaGalleries ), __('Multimedia galleries'), 'fa-th-large' ) );
    }
    if( $formContacto ) {
      $this->template->addToBlock( 'col8', $this->getPanelBlock( implode( "\n", $formContacto ), __('Contact'), 'fa-archive' ) );
    }


    // Bloques de 4
    if( $formPublished ) {
      $this->template->addToBlock( 'col4', $this->getPanelBlock( implode( "\n", $formPublished ), __( 'Publication' ), 'fa-adjust' ) );
    }
    if( $formImage ) {
      $this->template->addToBlock( 'col4', $this->getPanelBlock( implode( "\n", $formImage ), __( 'Select a image' ), 'fa-file-image-o' ) );
    }
    if( $formStatus ) {
      $this->template->addToBlock( 'col4', $this->getPanelBlock( implode( "\n", $formStatus ), __( 'Status' ) ) );
    }
    if( $formSeo ) {
      $this->template->addToBlock( 'col4', $this->getPanelBlock( implode( "\n", $formSeo ), __( 'SEO' ), 'fa-globe' ) );
    }

    $this->template->exec();
  }

  /**
    Creacion/Edicion de Recursos type URL
   */

  public function resourceTypeUrlForm( $urlParams = false ) {
    $formName = 'resourceUrlCreate';
    $formUrl = '/admin/resourcetypeurl/sendresource';

    $reqFields = array(
      'cgIntFrmId',
      'id',
      'rTypeId',
      'title',
      'shortDescription',
      'content',
      'externalUrl',
      'image',
      'rExtUrl_urlContentType',
      'rExtUrl_embed',
      'rExtUrl_author',
      'submit'
    );

    $resourceView = new GeozzyResourceView();
    $rtypeControl = new ResourcetypeModel();
    $rtype = $rtypeControl->listItems( array( 'filters' => array('idName' => 'rtypeUrl') ) )->fetch();

    $recursoData['rTypeId'] = $rtype->getter('id');

    $form = $resourceView->getFormObj( $formName, $formUrl, $recursoData );

    $allResourceFields = $form->getFieldsNamesArray();
    $noFields = array();
    $yesFields = array();
    foreach ( $reqFields as $key => $field ) {
      if( in_array( $field, $allResourceFields ) ){
        array_push( $yesFields, $field );
      }else{
        $mfield = $form->multilangFieldNames($field);
        foreach ($mfield as $k => $mf) {
          if( in_array( $mf, $allResourceFields) ){
            array_push( $yesFields, $mf );
          }
        }
      }
    }
    $noFields = array_diff( $allResourceFields, $yesFields );
    $form->removeField( $noFields );
    $formBlock = $resourceView->formToTemplate( $form );

    // Cambiamos el template del formulario
    $formBlock->setTpl( 'resourceTypeFormBlockBase.tpl', 'admin' );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );

    $this->template->addToBlock( 'col12', $this->getPanelBlock( $formBlock, __('Resource'), 'fa-archive' ) );
    $this->template->exec();
  } // function resourceForm()


  /**
    Creacion/Edicion de Recursos type FILE
   */

  public function resourceTypeFileForm( $urlParams = false ) {
    $formName = 'resourceFileCreate';
    $formUrl = '/admin/resourcetypefile/sendresource';

    //multilangFieldNames

    $reqFields = array(
      'cgIntFrmId',
      'id',
      'rTypeId',
      'title',
      'shortDescription',
      'content',
      'image',
      'rExtFile_file',
      'rExtFile_author',
      'submit'
    );


    $resourceView = new GeozzyResourceView();
    $rtypeControl = new ResourcetypeModel();
    $rtype = $rtypeControl->listItems( array( 'filters' => array('idName' => 'rtypeFile') ) )->fetch();

    $recursoData['rTypeId'] = $rtype->getter('id');
    // $formBlock = $resourceView->getFormBlock( $formName, $formUrl, $recursoData );
    $form = $resourceView->getFormObj( $formName, $formUrl, $recursoData );
    $allResourceFields = $form->getFieldsNamesArray();
    $noFields = array();
    $yesFields = array();
    foreach ( $reqFields as $key => $field ) {
      if( in_array( $field, $allResourceFields ) ){
        array_push( $yesFields, $field );
      }else{
        $mfield = $form->multilangFieldNames($field);
        foreach ($mfield as $k => $mf) {
          if( in_array( $mf, $allResourceFields) ){
            array_push( $yesFields, $mf );
          }
        }
      }
    }
    $noFields = array_diff( $allResourceFields, $yesFields );
    $form->removeField( $noFields );
    $formBlock = $resourceView->formToTemplate( $form );


    // Cambiamos el template del formulario
    $formBlock->setTpl( 'resourceTypeFormBlockBase.tpl', 'admin' );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );

/*
    // Fragmentamos el formulario generado
    $noFields = $this->extractFormBlockFields( $formBlock,
      array(
        'published', 'topics', 'starred', 'urlAlias', 'headKeywords', 'headDescription', 'headTitle', 'datoExtra1', 'datoExtra2',
        'collections', 'addCollections', 'multimediaGalleries', 'addMultimediaGalleries', 'locLat', 'locLon', 'defaultZoom'
      )
    );
*/

    $this->template->addToBlock( 'col12', $this->getPanelBlock( $formBlock, __('Resource'), 'fa-archive' ) );
    $this->template->exec();
  } // function resourceForm()




/*
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
*/






  public function sendResourceForm() {
    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  } // sendResourceForm()

  public function sendModalResourceForm() {
    $resourceView = new GeozzyResourceView();
    $resource = null;

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $resourceView->defResCtrl->resFormLoad();

    if( !$form->existErrors() ) {
      // Validar y guardar los datos
      $resource = $resourceView->actionResourceFormProcess( $form );
    }

    if( !$form->existErrors() && $resource ) {
      $form->removeSuccess( 'redirect' );
      $form->setSuccess( 'jsEval', ' successResourceForm( { id : "'.$resource->getter('id').'", title: "'.$resource->getter('title_'.$form->langDefault).'", image: "'.$resource->getter('image').'" });' );

      // Enviamos el OK-ERROR a la BBDD y al formulario
      $resourceView->actionResourceFormSuccess( $form, $resource );
    }

  }

}
