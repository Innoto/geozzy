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
  public function listResourcesOutTopic() {

    $template = new Template( $this->baseDir );
    $template->assign('resourceouttopicTable', table::getTableHtml('AdminViewResourceOutTopic', '/admin/resourceouttopic/table') );
    $template->setTpl('listResourceOutTopic.tpl', 'admin');

    $this->template->addToBlock( 'col12', $template );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  public function listResourcesOutTopicTable() {

    table::autoIncludes();
    $resourceouttopic =  new ResourceModel();

    $tabla = new TableController( $resourceouttopic );

    $tabla->setTabs(__('asigned'), array('1'=>__('Asigned'), '0'=>__('Unasigned'), '*'=> __('All') ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set table Actions
    $tabla->setActionMethod(__('Asign'), 'changeStatusAsigned', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "asigned", "changeValue"=>1 ))');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // Nome das columnas
    $tabla->setCol('id', 'Id');
    $tabla->setCol('type', __('Type'));
    $tabla->setCol('title_'.LANG_DEFAULT, __('Title'));
    $tabla->setCol('user', __('User'));

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}

