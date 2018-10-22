<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyResourceView.php' );



class AdminViewPage extends AdminViewMaster {

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list user
  **/
  public function listResourcesPage( ) {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('page:list', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $template = new Template( $this->baseDir );
    $template->assign('resourcepageTable', table::getTableHtml('AdminViewPage', '/admin/resourcepage/table') );
    $template->setTpl('listResourcePage.tpl', 'admin');

    $resourcetype =  new ResourcetypeModel();
    $resourcetype = $resourcetype->listItems(array( 'filters' => array( 'idName' => 'rtypePage' ) ))->fetch();

    $resCreateByType = '<div class="dropdown-menu dropdown-menu-right">';
      $resCreateByType .= '<a class="dropdown-item create-'.$resourcetype->getter('idName').'" href="/admin#resource/create/resourcetype/'.$resourcetype->getter('id').'">'.$resourcetype->getter('name').'</a>';
    $resCreateByType .= '</div>';

    $this->template->assign( 'headTitle', __('Resource Management') );
    $this->template->assign( 'headActions', '<div class="btn-group assignResource AdminViewResource">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>'
    );
    $this->template->assign( 'footerActions', '<div class="btn-group assignResource AdminViewResource">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>' );

    $this->template->addToFragment( 'col12', $template );

    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }


  public function listResourcesPageTable() {

    $resourcetype =  new ResourcetypeModel();
    $resourcetype = $resourcetype->listItems(array( 'filters' => array( 'idName' => 'rtypePage' ) ))->fetch();
    $type = $resourcetype->getter('id');

    table::autoIncludes();
    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    $tabla->setTabs('published', array('1'=>__('Published'), '0'=>__('Unpublished'), '*'=> __('All') ), '*');

    // set id search reference.
    $tabla->setSearchRefId('find');

    // set table Actions
    $tabla->setActionMethod(__('Publish'), 'changeStatusPublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>1 ))');
    $tabla->setActionMethod(__('Unpublish'), 'changeStatusUnpublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>0 ))');
    $tabla->setActionMethod(__('Delete'), 'delete', 'listitems(array("filters" => array("id" => $rowId)))->fetch()->delete()');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#resource/edit/id/".$rowId."/type/'.$type.'"');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'ID');
    $tabla->setCol('rTypeId', __('Type'));
    $tabla->setCol('title_'.Cogumelo::getSetupValue( 'lang:default' ), __('Title'));
    $tabla->setCol('published', __('Published'));

    // Filtrar por tipo

    $tabla->setDefaultFilters( array('rTypeId'=> $type ) );


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

}
