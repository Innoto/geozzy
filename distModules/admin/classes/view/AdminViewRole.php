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
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('user:all', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $template = new Template( $this->baseDir );
    $template->assign('roleTable', table::getTableHtml('AdminViewRole', '/admin/role/table') );
    $template->setTpl('listRole.tpl', 'admin');

    $this->template->addToFragment( 'col12', $template );
    $this->template->assign( 'headTitle', __('Roles Management') );
    $this->template->assign( 'headActions', '<a href="/admin#role/create" class="btn btn-default"> '.__('Add role').'</a>' );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
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
    $tabla->setEachRowUrl('"/admin#role/edit/id/".$rowId');
    $tabla->setNewItemUrl('/admin#role/create');

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
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('user:all', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $roleView = new RoleView();

    $form = $roleView->roleFormDefine();
    $form->setAction('/admin/role/sendrole');
    $form->setSuccess( 'redirect', '/admin#role/list' );

    $createRoleHtml = $roleView->roleFormGet( $form );

    $template = new Template( $this->baseDir );
    $template->assign('createRoleHtml', $createRoleHtml);
    $template->setTpl('createRole.tpl', 'admin');

    $this->template->addToFragment( 'col8', $template );
    $this->template->assign( 'headTitle', __('Create role') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $this->template->exec();
  }

  /**
  * Section edit role
  **/

  function editRole( $urlParams ) {
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('user:all', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $validation = array( 'id'=> '#^\d+$#' );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );
    $roleView = new RoleView();

    /*FORM EDIT*/
    $form = $roleView->roleUpdateFormDefine( $urlParamsList['id'] );
    $form->setAction('/admin/role/sendrole');
    $form->setSuccess( 'redirect', '/admin#role/list' );
    $editRoleHtml = $roleView->roleFormGet( $form );


    $template = new Template( $this->baseDir );
    $template->assign('editRoleHtml', $editRoleHtml);
    $template->setTpl('editRole.tpl', 'admin');

    $this->template->addToFragment( 'col8', $template );
    $this->template->assign( 'headTitle', __('Edit role') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $this->template->exec();
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
