<?php
admin::load('view/AdminViewMaster.php');
Cogumelo::load("coreController/RequestController.php");

class AdminViewResourceOutTopic extends AdminViewMaster {

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list resource
  **/
  public function listResourcesOutTopic( $urlParams ) {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('topic:assign', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $validation = array('topic'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams,$validation);

    $topicId = $urlParamsList['topic'];

    $template = new Template( $this->baseDir );
    $template->assign('resourceouttopicTable', table::getTableHtml('AdminViewResourceOutTopic', '/admin/resourceouttopic/table/topic/'.$topicId ) );
    $template->setTpl('listResourceOutTopic.tpl', 'admin');

    $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems( array( 'filters' => array( 'intopic' => $topicId ) ) )->fetchAll();

    $resCreateByType = '<div class="dropdown-menu dropdown-menu-right">';
    foreach( $resourcetypelist as $i => $rType ) {
      //$typeList[ $i ] = $rType->getter('name');
      $resCreateByType .= '<a class="dropdown-item create-'.$rType->getter('idName').'" href="/admin#resource/create/topic/'.$topicId.'/resourcetype/'.$rType->getter('id').'">'.$rType->getter('name').'</a>';
    }
    $resCreateByType .= '</div>';


    $this->template->assign( 'headTitle', __('Create and add resources') );
    $this->template->assign( 'headActions', '<a href="/admin#topic/'.$topicId.'" class="btn btn-default"> '.__('Return').'</a>
      <div class="btn-group assignResource AdminViewResourceOutTopic">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>
      <div class="btn btn-primary assignResource btnAssign"> '.__('Assign selected').'</div>'
    );

    $this->template->assign( 'footerActions', '<a href="/admin#topic/'.$topicId.'" class="btn btn-default"> '.__('Return').'</a>
      <div class="btn-group assignResource">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>
      <div class="btn btn-primary assignResource btnAssign"> '.__('Assign selected').'</div>'
    );

    $this->template->addToFragment( 'col8', $template );

    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $panel = $this->getPanelBlock( '<ul style="list-style:none;"><li>'. __("Create a new resource") .'</li><li>'. __("Working with resource types") .'</li><li>'. __("Assign to this topic") .'</li></ul>'. __("Assign resources: howto")  );
    $this->template->addToFragment( 'col4', $panel );
    $this->template->exec();
  }

  public function listResourcesOutTopicTable( $urlParams ) {
    $useraccesscontrol = new UserAccessController();
    $validation = array('topic'=> '#\d+$#','resourceId'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams,$validation);
    $topicId = $urlParamsList['topic'];

    $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems( array( 'filters' => array( 'intopic' => $topicId ) ) )->fetchAll();

    foreach ($resourcetypelist as $typeId => $type){
      $tiposArray[$typeId] = $typeId;
    }

    table::autoIncludes();
    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    $tabla->setTabs(__('id'), array('*'=> __('All') ), '*');


    // set id search reference.
    $tabla->setSearchRefId('find');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#resource/edit/id/".$rowId');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'ID');
    $tabla->setCol('rTypeId', __('Type'));
    $tabla->setCol('title_'.Cogumelo::getSetupValue( 'lang:default' ), __('Title'));

    $tabla->setActionMethod(__('Assign'), 'assign', 'createTopicRelation('.$topicId.',$rowId)');

    // Contido especial
  //  $typeModel =  new ResourcetypeModel();
  //  $typeList = $typeModel->listItems()->fetchAll();
    foreach ($resourcetypelist as $id => $type){
      $tabla->colRule('rTypeId', '#'.$id.'#', $type->getter('name'));
    }

    // Filtrar por temática
    $userSession = $useraccesscontrol->getSessiondata();
    if($userSession && in_array('resource:mylist', $userSession['permissions'])){
      $filters = array( 'nottopic'=> $topicId, 'inRtype'=>$tiposArray, 'user' => $userSession['data']['id'] );
    }else{
      $filters =  array('nottopic'=> $topicId, 'inRtype'=>$tiposArray);
    }
    $tabla->setDefaultFilters($filters);

    // imprimimos o JSON da taboa
    $tabla->exec();
  }


}
