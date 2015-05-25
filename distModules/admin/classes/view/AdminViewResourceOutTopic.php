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

    $this->template->assign( 'headTitle', __('Create and add resources') );
    $this->template->assign( 'headActions', '<button type="button" class="btn btn-default btn-outline"> '.__('Return').'</button>
                                             <div class="btn-group assignResource">
                                              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Crear <span class="caret"></span>
                                              </button>
                                              <ul class="dropdown-menu" role="menu">
                                                <li><a href="#">Create</a></li>
                                                <li><a href="#">Another action</a></li>
                                                <li><a href="#">Something else here</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Separated link</a></li>
                                              </ul>
                                            </div>
                                             <div id="topAssign" class="btn btn-primary assignResource"> '.__('Assign selected').'</div>' );
                                            
    $this->template->assign( 'footerActions', '<button type="button" class="btn btn-default"> '.__('Return').'</button>
                                             <div class="btn-group assignResource">
                                              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Crear <span class="caret"></span>
                                              </button>
                                              <ul class="dropdown-menu" role="menu">
                                                <li><a href="#">Create</a></li>
                                                <li><a href="#">Another action</a></li>
                                                <li><a href="#">Something else here</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Separated link</a></li>
                                              </ul>
                                            </div>
                                             <button type="button" class="btn btn-primary assignResource"> '.__('Assign selected').'</button>' );    
    $this->template->addToBlock( 'col8', $template );
    
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );
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
    $tabla->setCol('type', __('Type'));
    $tabla->setCol('title_'.LANG_DEFAULT, __('Title'));

    $tabla->setActionMethod(__('Assign'), 'assign', 'createRelation('.$topicId[1].',$rowId)');

    // Filtrar por temática
    $tabla->setDefaultFilters( array('nottopic'=> $topicId[1] ) );

    // imprimimos o JSON da taboa
    $tabla->exec();
  }


}

