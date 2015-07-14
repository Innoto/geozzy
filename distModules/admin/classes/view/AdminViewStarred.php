<?php
admin::load('view/AdminViewMaster.php');


class AdminViewStarred extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list resources in topic
  **/
  public function listAssignStarred($request) {

    $template = new Template( $this->baseDir );
    $template->assign('starredTable', table::getTableHtml('AdminViewStarred', '/admin/starred/table/'.$request[1]) );
    $template->setTpl('listAsignStarred.tpl', 'admin');

      $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems(array("filters" => array("notintaxonomyterm" => $request['1'])))->fetchAll();

    $part = '<ul class="dropdown-menu" role="menu">';
    foreach ($resourcetypelist as $i => $res){
      $typeList[$i] = $res->getter('name_es');
      $part = $part.'<a id="'.$res->getter('idName').'" href="/admin#resource/create/'.$request['1'].'/'.$res->getter('id').'">'.$res->getter('name_es').'</a><br>';
    }
    $part = $part.'</ul>';

    $this->template->assign( 'headTitle', __('Create and add resources') );
    $this->template->assign( 'headActions', '<a href="/admin#starred/'.$request['1'].'" class="btn btn-default btn-outline"> '.__('Return').'</a>
                                             <div class="btn-group assignResource">
                                              <button type="button" class="btn btn-primary dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
                                                '.__('Crear').' <span class="caret"></span>
                                              </button>
                                              '.$part.'
                                             </div>
                                             <div class="btn btn-primary assignResource btnAssign"> '.__('Assign selected').'</div>' );
                                            
    $this->template->assign( 'footerActions', '<a href="/admin#starred/'.$request['1'].'" class="btn btn-default"> '.__('Return').'</a>
                                             <div class="btn-group assignResource">
                                              <button type="button" class="btn btn-primary dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
                                                '.__('Crear').' <span class="caret"></span>
                                              </button>
                                              '.$part.'
                                             </div>
                                             <div class="btn btn-primary assignResource btnAssign"> '.__('Assign selected').'</div>' ); 
    
    $this->template->addToBlock( 'col8', $template );
    
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $panel = $this->getPanelBlock( __('<ul style="list-style:none;"><li>Create a new resource</li><li>Working with resource types)</li><li>Assign to this starred)</li></ul>'), __('Assign resources: howto') );
    $this->template->addToBlock( 'col4', $panel );
    $this->template->exec();
  }

  public function listStarredTable($starredId) {

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

    $tabla->setActionMethod(__('Assign'), 'assign', 'createTaxonomytermRelation('.$starredId[1].',$rowId)');

    // Filtrar por temática
    $tabla->setDefaultFilters( array('notintaxonomyterm'=> $starredId[1] ) );

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}

