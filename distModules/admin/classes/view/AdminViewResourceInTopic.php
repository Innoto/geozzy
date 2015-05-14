<?php
admin::load('view/AdminViewMaster.php');


class AdminViewResourceInTopic extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list resources in topic
  **/
  public function listResourcesInTopic() {

    $this->template->assign('resourceintopicTable', table::getTableHtml('AdminViewResourceInTopic', '/admin/resourceintopic/table') );
    $this->template->setTpl('listResourceInTopic.tpl', 'admin');
    $this->template->exec();
  }

  public function listResourcesInTopicTable() {

    table::autoIncludes();
    $resourceintopic =  new ResourceModel();

    $tabla = new TableController( $resourceintopic );

    $tabla->setTabs(__('asigned'), array('1'=>__('Asigned'), '0'=>__('Unasigned'), '*'=> __('All') ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set table Actions
    $tabla->setActionMethod(__('Asign'), 'changeStatusAsigned', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "asigned", "changeValue"=>1 ))');
    $tabla->setActionMethod(__('Unasign'), 'changeStatusUnasigned', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "unasigned", "changeValue"=>0 ))');
    $tabla->setActionMethod(__('Delete'), 'changeStatusDeleted', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "delete", "changeValue"=>1 ))');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#resource/edit/".$rowId');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'Id');
    $tabla->setCol('type', __('Type'));
    $tabla->setCol('title', __('Title'));
    $tabla->setCol('user', __('User'));

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}

