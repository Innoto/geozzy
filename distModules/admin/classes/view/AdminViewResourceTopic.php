<?php
admin::load('view/AdminViewMaster.php');


class AdminViewResourceTopic extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list user
  **/
  public function listResourcesTopic() {

    $this->template->assign('resourcetopicTable', table::getTableHtml('AdminViewResourceTopic', '/admin/resourcetopic/table') );
    $this->template->setTpl('listResourceTopic.tpl', 'admin');
    $this->template->exec();
  }


  /**
  * Section list resource
  **/
  public function listResourcesTopicBlock() {
    $template = new Template( $this->baseDir );

    $template->assign('resourcetopicTable', table::getTableHtml('AdminViewResourceTopic', '/admin/resourcetopic/table') );
    $template->setTpl('listResourceTopic.tpl', 'admin');

    return $template;
  }


  public function listResourcesTopicTable() {

    table::autoIncludes();
    $resourcetopic =  new ResourceModel();

    $tabla = new TableController( $resourcetopic );

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

  /**
  * Section create resource
  **/

  public function createResource() {

    $resourceView = new ResourceView();

    $form = $resourceView->resourceFormDefine();
    $form->setAction('/admin/resource/sendresource');
    $form->setSuccess( 'redirect', '/admin#resource/list' );

    $createResourceHtml = $userView->resourceFormGet( $form );
    $this->template->assign('createResourceHtml', $createResourceHtml);
    $this->template->setTpl('createResource.tpl', 'admin');

    $this->template->exec();

  }

  /**
  * Section edit resource
  **/
  public function editResource( $request ) {

    $resourceView = new ResourceView();

    /*FORM EDIT*/
    $form = $resourceView->userUpdateFormDefine($request);
    $form->setAction('/admin/resource/sendresource');
    $form->setSuccess( 'redirect', '/admin#resource/list' );
    $editResourceHtml = $resourceView->resourceFormGet( $form );
    $this->template->assign('editResourceHtml', $editResourceHtml);
    /*--------------------*/

    $this->template->setTpl('editResourcetpl', 'admin');

    $this->template->exec();

  }


  /**
   Action resourceForm
  */
  public function sendResourceForm() {

    $resourceView = new ResourceView();

    $form = $resourceView->actionResourceForm();
    if( $form->existErrors() ) {
      echo $form->getJsonError();
    }
    else {
      $userView->resourceFormOk( $form );
      echo $form->getJsonOk();
    }
  }

}

