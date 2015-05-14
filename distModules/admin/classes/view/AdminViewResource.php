<?php
admin::load('view/AdminViewMaster.php');


class AdminViewResource extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list user
  **/
  public function listResources() {

    $this->template->assign('resourceTable', table::getTableHtml('AdminViewResource', '/admin/resource/table') );
    $this->template->setTpl('listResource.tpl', 'admin');
    $this->template->exec();
  }


  /**
  * Section list resource
  **/
  public function listResourcesBlock() {
    $template = new Template( $this->baseDir );

    $template->assign('resourceTable', table::getTableHtml('AdminViewResource', '/admin/resource/table') );
    $template->setTpl('listResource.tpl', 'admin');

    return $template;
  }


  public function listResourcesTable() {

    table::autoIncludes();
    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    $tabla->setTabs(__('published'), array('1'=>__('Published'), '0'=>__('Unpublished'), '*'=> __('All') ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set table Actions
    $tabla->setActionMethod(__('Publish'), 'changeStatusPublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>1 ))');
    $tabla->setActionMethod(__('Unpublish'), 'changeStatusUnpublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "unpublished", "changeValue"=>0 ))');
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
    $tabla->setCol('timeCreation', __('Time Creation'));
    $tabla->setCol('timeLastUpdate', __('Time Update'));
    $tabla->setCol('published', __('Published'));

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

