<?php
admin::load('view/AdminViewMaster.php');


class AdminViewResourceOutTopic extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list resource
  **/
  public function listResourcesOutTopic($request) {

    $template = new Template( $this->baseDir );
    $template->assign('resourceouttopicTable', table::getTableHtml('AdminViewResourceOutTopic', '/admin/resourceouttopic/table/'.$request['1']) );
    $template->setTpl('listResourceOutTopic.tpl', 'admin');

    $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems(array("filters" => array("intopic" => $request['1'])))->fetchAll();

    $part = '<ul class="dropdown-menu" role="menu">';
    foreach ($resourcetypelist as $i => $res){
      $typeList[$i] = $res->getter('name_es');
      $part = $part.'<a id="'.$res->getter('idName').'" href="/admin#resource/create/'.$request['1'].'/'.$res->getter('id').'">'.$res->getter('name_es').'</a><br>';
    }
    $part = $part.'</ul>';

    $this->template->assign( 'headTitle', __('Create and add resources') );
    $this->template->assign( 'headActions', '<a href="/admin#resourceintopic/list/'.$request['1'].'" class="btn btn-default"> '.__('Return').'</a>
                                             <div class="btn-group assignResource">
                                              <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
                                                '.__('Crear').' <span class="caret"></span>
                                              </button>
                                              '.$part.'
                                             </div>
                                             <div class="btn btn-primary assignResource btnAssign"> '.__('Assign selected').'</div>' );

    $this->template->assign( 'footerActions', '<a href="/admin#resourceintopic/list/'.$request['1'].'" class="btn btn-default"> '.__('Return').'</a>
                                             <div class="btn-group assignResource">
                                              <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
                                                '.__('Crear').' <span class="caret"></span>
                                              </button>
                                              '.$part.'
                                             </div>
                                             <div class="btn btn-primary assignResource btnAssign"> '.__('Assign selected').'</div>' );
    $this->template->addToBlock( 'col8', $template );

    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $panel = $this->getPanelBlock( __('<ul style="list-style:none;"><li>Create a new resource)</li><li>Working with resource types)</li><li>Assign to this topic)</li></ul>'), __('Assign resources: howto') );
    $this->template->addToBlock( 'col4', $panel );
    $this->template->exec();
  }

  public function listResourcesOutTopicTable($topicId) {

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
    $tabla->setEachRowUrl('"/admin#resource/edit/".$rowId');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'ID');
    $tabla->setCol('rTypeId', __('Type'));
    $tabla->setCol('title_'.LANG_DEFAULT, __('Title'));

    $tabla->setActionMethod(__('Assign'), 'assign', 'createTopicRelation('.$topicId[1].',$rowId)');

    // Contido especial
    $typeModel =  new ResourcetypeModel();
    $typeList = $typeModel->listItems()->fetchAll();
    foreach ($typeList as $id => $type){
      $tabla->colRule('rTypeId', '#'.$id.'#', $type->getter('name'));
    }

    // Filtrar por temática
    $tabla->setDefaultFilters( array('nottopic'=> $topicId[1] ) );

    // imprimimos o JSON da taboa
    $tabla->exec();
  }


}
