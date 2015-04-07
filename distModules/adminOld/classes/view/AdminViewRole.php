<?php
admin::load('view/AdminViewMaster.php');


class AdminViewRole extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  /**
  * Section list user
  **/

  function listRoles() {

    table::autoIncludes();
    $this->template->assign('roleTable', table::getTableHtml('AdminViewRole', '/admin/role/table') );
    $this->template->setTpl('listRole.tpl', 'admin');
    $this->commonAdminInterface();
  }

  function listRolesTable(){

    table::autoIncludes();
    $roleModel =  new RoleModel();

    $tabla = new TableController( $roleModel );

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin/role/edit/".$rowId');
    $tabla->setNewItemUrl('/admin/role/create');

    // Nome das columnas
    $tabla->setCol('id', 'Id');
    $tabla->setCol('name', 'Name');
    $tabla->setCol('description', 'Description');

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

  /**
  * Section create role
  **/

  function createRole() {

    $roleView = new RoleView();

    $form = $roleView->roleFormDefine();
    $form->setAction('/admin/role/sendrole');
    $form->setSuccess( 'redirect', '/admin/role/list' );

    $createRoleHtml = $roleView->roleFormGet( $form );
    $this->template->assign('createRoleHtml', $createRoleHtml);
    $this->template->setTpl('createRole.tpl', 'admin');

    $this->commonAdminInterface();

  }

  /**
  * Section edit role
  **/

  function editRole($request) {

    $roleView = new RoleView();

    /*FORM EDIT*/
    $form = $roleView->roleUpdateFormDefine($request);
    $form->setAction('/admin/role/sendrole');
    $form->setSuccess( 'redirect', '/admin/role/list' );
    $editRoleHtml = $roleView->roleFormGet( $form );
    $this->template->assign('editRoleHtml', $editRoleHtml);
    /*--------------------*/

    $this->template->setTpl('editRole.tpl', 'admin');
    $this->commonAdminInterface();

  }


  /**
   Action roleForm
  */
  function sendRoleForm() {

    $roleView = new RoleView();

    $form = $roleView->actionRoleForm();
    if( $form->existErrors() ) {
      echo $form->getJsonError();
    }
    else {
      $roleView->roleFormOk( $form );
      echo $form->getJsonOk();
    }
  }


}

