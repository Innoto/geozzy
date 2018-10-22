<?php
admin::load('view/AdminViewMaster.php');
Cogumelo::load("coreController/RequestController.php");

class AdminViewStarred extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list resources in topic
  **/
  public function listAssignStarred( $urlParams ) {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('starred:list', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $validation = array('star'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);
    $starredId = $urlParamsList['star'];

    $template = new Template( $this->baseDir );
    $template->assign('starredTable', table::getTableHtml( 'AdminViewStarred', '/admin/starred/table/'.$starredId ) );
    $template->setTpl('listAsignStarred.tpl', 'admin');

    $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems( array( 'filters' => array( 'notintaxonomyterm' => $starredId ) ) )->fetchAll();

    $resCreateByType = '<div class="dropdown-menu dropdown-menu-right">';
    foreach( $resourcetypelist as $i => $rType ) {
      $typeList[ $i ] = $rType->getter('name');
      $resCreateByType .= '<a class="dropdown-item create-'.$rType->getter('idName').'" href="/admin#resource/create/star/'.$starredId.'/resourcetype/'.$rType->getter('id').'">'.$rType->getter('name').'</a>';
    }
    $resCreateByType .= '</div>';

    $this->template->assign( 'headTitle', __('Create and add resources') );
    $this->template->assign( 'headActions', '<a href="/admin#starred/'.$starredId.'" class="btn btn-default"> '.__('Return').'</a>
      <div class="btn-group assignResource AdminViewStarred">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>
      <div class="btn btn-primary assignResource btnAssign"> '.__('Assign selected').'</div>'
    );

    $this->template->assign( 'footerActions', '<a href="/admin#starred/'.$starredId.'" class="btn btn-default"> '.__('Return').'</a>
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

    $panel = $this->getPanelBlock( __('<ul style="list-style:none;"><li>Create a new resource</li><li>Working with resource types)</li><li>Assign to this starred)</li></ul>'), __('Assign resources: howto') );
    $this->template->addToFragment( 'col4', $panel );
    $this->template->exec();
  }

  public function listStarredTable( $starredId ) {

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

    $tabla->setActionMethod(__('Assign'), 'assign', 'createTaxonomytermRelation('.$starredId[1].',$rowId)');

    // Contido especial
    $typeModel =  new ResourcetypeModel();
    $typeList = $typeModel->listItems()->fetchAll();
    foreach ($typeList as $id => $type){
      $tabla->colRule('rTypeId', '#'.$id.'#', $type->getter('name'));
    }

    // Filtrar por temática
    $tabla->setDefaultFilters( array('notintaxonomyterm'=> $starredId[1] ) );

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}
