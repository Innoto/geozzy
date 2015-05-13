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

    $tabla->setTabs('active', array('1'=>'Activos', '0'=>'Bloqueados', '*'=> 'Todos' ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set table Actions
    $tabla->setActionMethod('Activar', 'changeStatusActive', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "active", "changeValue"=>1 ))');
    $tabla->setActionMethod('Bloquear', 'changeStatusLock', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "active", "changeValue"=> 0 ))');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#resource/edit/".$rowId');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'Id');
    $tabla->setCol('title', 'Title');

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

