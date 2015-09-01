<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyResourceView.php' );


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
      $resCreateByType .= '<li><a class="create-'.$res->getter('idName').'" href="/admin#resource/create/all/'.$res->getter('id').'">'.$res->getter('name_es').'</a></li>';
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
    $formName = 'resourceCreate';
    $formUrl = '/admin/resource/sendresource';

    $resourceView = new GeozzyResourceView();

    if( $urlParams ) {

      $urlParamTopic = $urlParams['1'];
      $urlParamRtype = $urlParams['2'];

      if ( $urlParamTopic != 'all') {
        $topicControl = new TopicModel();
        $topicItem = $topicControl->ListItems( array( 'filters' => array( 'id' => $urlParamTopic ) ) )->fetch();
      }

      $rtypeControl = new ResourcetypeModel();
      $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'id' => $urlParamRtype ) ) )->fetch();

      if( isset($topicItem) && $topicItem && $rTypeItem ){
        $rtypeTopicControl = new ResourcetypeTopicModel();
        $resourcetypeTopic = $rtypeTopicControl->ListItems( array( 'filters' => array( 'topic' => $urlParamTopic, 'resourceType' => $urlParamRtype ) ) )->fetch();

        if( !$resourcetypeTopic ){
          print('O.o ERROR!!!');
          exit();
        }else{
          $recursoData['topicReturn'] = $topicItem->getter('id');
          $recursoData['topics'] = $topicItem->getter('id');
          $recursoData['rTypeId'] = $rTypeItem->getter('id');
          $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');
        }
      }else{
        if( $rTypeItem ){
          $recursoData['rTypeId'] = $rTypeItem->getter('id');
          $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');
        }
      }
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
    $formName = 'resourceEdit';
    $formUrl = '/admin/resource/sendresource';

    $valuesArray = false;

    $urlParamIdResource = $urlParams['1'];

    if( isset( $urlParamIdResource ) ) {
      $resCtrl = new ResourceController();
      $valuesArray = $resCtrl->getResourceData( $urlParamIdResource );
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

    $resourceView = new GeozzyResourceView();
    $rtypeControl = new ResourcetypeModel();
    $rtype = $rtypeControl->listItems( array( 'filters' => array('idName' => 'rtypeUrl') ) )->fetch();

    $recursoData['rTypeId'] = $rtype->getter('id');
    $formBlock = $resourceView->getFormBlock( $formName, $formUrl, $recursoData );

    // Cambiamos el template del formulario
    $formBlock->setTpl( 'resourceTypeFormBlockBase.tpl', 'admin' );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );

    // Fragmentamos el formulario generado
    $noFields = $this->extractFormBlockFields( $formBlock,
      array(
        'published', 'topics', 'starred', 'urlAlias', 'headKeywords', 'headDescription', 'headTitle', 'datoExtra1', 'datoExtra2',
        'collections', 'addCollections', 'multimediaGalleries', 'addMultimediaGalleries', 'locLat', 'locLon', 'defaultZoom'
      )
    );

    $this->template->addToBlock( 'col12', $this->getPanelBlock( $formBlock, __('Resource'), 'fa-archive' ) );
    $this->template->exec();
  } // function resourceForm()


  /**
    Creacion/Edicion de Recursos type FILE
   */

  public function resourceTypeFileForm( $urlParams = false ) {
    $formName = 'resourceFileCreate';
    $formUrl = '/admin/resourcetypefile/sendresource';

    $resourceView = new GeozzyResourceView();
    $rtypeControl = new ResourcetypeModel();
    $rtype = $rtypeControl->listItems( array( 'filters' => array('idName' => 'rtypeFile') ) )->fetch();

    $recursoData['rTypeId'] = $rtype->getter('id');
    $formBlock = $resourceView->getFormBlock( $formName, $formUrl, $recursoData );

    // Cambiamos el template del formulario
    $formBlock->setTpl( 'resourceTypeFormBlockBase.tpl', 'admin' );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );

    // Fragmentamos el formulario generado
    $noFields = $this->extractFormBlockFields( $formBlock,
      array(
        'published', 'topics', 'starred', 'urlAlias', 'headKeywords', 'headDescription', 'headTitle', 'datoExtra1', 'datoExtra2',
        'collections', 'addCollections', 'multimediaGalleries', 'addMultimediaGalleries', 'locLat', 'locLon', 'defaultZoom'
      )
    );

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

}
