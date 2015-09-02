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

    $urlParamsList = RequestController::processUrlParams($urlParams[1]);

    $topicId = $urlParamsList['topic'];

    $template = new Template( $this->baseDir );
    $template->assign('resourceouttopicTable', table::getTableHtml('AdminViewResourceOutTopic', '/admin/resourceouttopic/table/topic/'.$topicId ) );
    $template->setTpl('listResourceOutTopic.tpl', 'admin');

    $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems( array( 'filters' => array( 'intopic' => $topicId ) ) )->fetchAll();

    $resCreateByType = '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
    foreach( $resourcetypelist as $i => $rType ) {
      $typeList[ $i ] = $rType->getter('name_es');
      $resCreateByType .= '<li><a class="create-'.$rType->getter('idName').'" href="/admin#resource/create/topic/'.$topicId.'/rType/'.$rType->getter('id').'">'.$rType->getter('name_es').'</a></li>';
    }
    $resCreateByType .= '</ul>';

    $this->template->assign( 'headTitle', __('Create and add resources') );
    $this->template->assign( 'headActions', '<a href="/admin#resourceintopic/list/'.$topicId.'" class="btn btn-default"> '.__('Return').'</a>
      <div class="btn-group assignResource AdminViewResourceOutTopic">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>
      <div class="btn btn-primary assignResource btnAssign"> '.__('Assign selected').'</div>'
    );

    $this->template->assign( 'footerActions', '<a href="/admin#resourceintopic/list/'.$topicId.'" class="btn btn-default"> '.__('Return').'</a>
      <div class="btn-group assignResource">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>
      <div class="btn btn-primary assignResource btnAssign"> '.__('Assign selected').'</div>'
    );

    $this->template->addToBlock( 'col8', $template );

    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $panel = $this->getPanelBlock( __('<ul style="list-style:none;"><li>Create a new resource)</li><li>Working with resource types)</li><li>Assign to this topic)</li></ul>'), __('Assign resources: howto') );
    $this->template->addToBlock( 'col4', $panel );
    $this->template->exec();
  }

  public function listResourcesOutTopicTable( $urlParams ) {

    $urlParamsList = RequestController::processUrlParams($urlParams[1]);
    $topicId = $urlParamsList['topic'];

    table::autoIncludes();
    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    $tabla->setTabs(__('id'), array('*'=> __('All') ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

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

    $tabla->setActionMethod(__('Assign'), 'assign', 'createTopicRelation('.$topicId.',$rowId)');

    // Contido especial
    $typeModel =  new ResourcetypeModel();
    $typeList = $typeModel->listItems()->fetchAll();
    foreach ($typeList as $id => $type){
      $tabla->colRule('rTypeId', '#'.$id.'#', $type->getter('name'));
    }

    // Filtrar por temática
    $tabla->setDefaultFilters( array('nottopic'=> $topicId ) );

    // imprimimos o JSON da taboa
    $tabla->exec();
  }


}
