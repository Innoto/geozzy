<?php
admin::load('view/AdminViewMaster.php');


class AdminViewUser extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section user profile
  **/
  public function showUser() {

    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();

    $template = new Template( $this->baseDir );
    $template->assign( 'user' , $user);
    $template->setTpl('showUser.tpl', 'admin');

    $this->template->addToBlock( 'col8', $template );
    $this->template->assign( 'headTitle', __('Show user') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $this->template->exec();
  }

  /**
  * Section list user
  **/


  public function listUsers() {

    $template = new Template( $this->baseDir );
    $template->assign('userTable', table::getTableHtml('AdminViewUser', '/admin/user/table') );
    $template->setTpl('listUser.tpl', 'admin');

    $this->template->addToBlock( 'col12', $template );
    $this->template->assign( 'headTitle', __('User Management') );
    $this->template->assign( 'headActions', '<a href="/admin#user/create" class="btn btn-default"> '.__('Add user').'</a>' );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  /*
    public function listUsers() {
      $listUsersBlock = $this->listUsersBlock();

      $panel = $this->getPanelBlock( $listUsersBlock, 'Users Table', 'fa-user' );
      $this->template->addToBlock( 'col12', $panel );
      $this->template->assign('titleHeader', "Users List");
      $this->template->setTpl( 'adminContent-12.tpl', 'admin' );

      $this->template->exec();
    }
    public function listUsersBlock() {
      $template = new Template( $this->baseDir );

      $template->assign('userTable', table::getTableHtml('AdminViewUser', '/admin/user/table') );
      $template->setTpl('listUser.tpl', 'admin');

      return $template;
    }
  */



  public function listUsersTable() {

    table::autoIncludes();
    $user =  new UserModel();

    $tabla = new TableController( $user );

    $tabla->setTabs('active', array('1'=>'Activos', '0'=>'Bloqueados', '*'=> 'Todos' ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set table Actions
    $tabla->setActionMethod(__('Active'), 'changeStatusActive', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "active", "changeValue"=>1 ))');
    $tabla->setActionMethod(__('Inactive'), 'changeStatusLock', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "active", "changeValue"=> 0 ))');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#user/edit/id/".$rowId');
    $tabla->setNewItemUrl('/admin#user/create');

    // Nome das columnas
    $tabla->setCol('id', __('Id'));
    $tabla->setCol('login', __('Login'));
    $tabla->setCol('name', __('Name'));
    $tabla->setCol('surname', __('Surname'));
    $tabla->setCol('email', __('Email'));
    $tabla->setCol('active', __('Active'));
    //$tabla->setCol('role', 'Role');

    // Contido especial
    $tabla->colRule('active', '#1#', '<span class=\"rowMark rowOk\"><i class=\"fa fa-circle\"></i></span>');
    $tabla->colRule('active', '#0#', '<span class=\"rowMark rowNo\"><i class=\"fa fa-circle\"></i></span>');

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

  /**
  * Section create user
  **/

  public function createUser() {

    $userView = new UserView();

    $form = $userView->userFormDefine();
    $form->setAction('/admin/user/senduser');
    $form->setSuccess( 'redirect', '/admin#user/list' );

    $createUserBlock = $userView->userFormGetBlock( $form );
    $createUserBlock->setTpl('createUser.tpl', 'admin');


    $this->template->addToBlock( 'col8', $createUserBlock );
    $this->template->assign( 'headTitle', __('Create user') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $this->template->exec();


  }

  /**
  * Section edit user
  **/
  public function editUser( $urlParams ) {

    $validation = array( 'id'=> '#^\d+$#' );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    $userView = new UserView();

    /*FORM EDIT*/
    $form = $userView->userUpdateFormDefine($urlParamsList['id']);
    $form->setAction('/admin/user/senduser');
    $form->setSuccess( 'redirect', '/admin#user/list' );
    $editUserBlock = $userView->userFormGetBlock( $form );
    $editUserBlock->setTpl('editUser.tpl', 'admin');
    $this->template->addToBlock( 'col8', $editUserBlock );
    /*--------------------*/

    /*FORM CHANGE PASSWORD*/
    $formChange = $userView->userChangePasswordFormDefine($urlParamsList['id']);
    $formChange->setAction('/admin/user/changepassword');
    $formChange->setSuccess( 'redirect', '/admin#user/list' );
    $changePasswordBlock = $userView->userChangePasswordFormGetBlock( $formChange );
    $this->template->addToBlock( 'col4', $this->getPanelBlock( $changePasswordBlock, 'Change Password', 'fa-key' ) );

    /*--------------------*/

    /*FORM ASSIGN ROLES*/
    $userRolesForm = $userView->userRolesFormDefine($urlParamsList['id']);
    $userRolesForm->setAction('/admin/user/assignroles');
    $userRolesForm->setSuccess( 'redirect', '/admin#user/list' );
    $userRolesFormBlock = $userView->userRolesFormGetBlock( $userRolesForm );
    $this->template->addToBlock( 'col4', $this->getPanelBlock( $userRolesFormBlock, 'Edit Roles', 'fa-unlock-alt' ) );
    /*--------------------*/

    $this->template->assign( 'headTitle', __('Edit user') );
    $this->template->setTpl('adminContent-8-4.tpl', 'admin');

    $this->template->exec();

  }


  /**
   Action userForm
  */
  public function sendUserForm() {

    $userView = new UserView();

    $form = $userView->actionUserForm();
    if( $form->existErrors() ) {
      echo $form->getJsonError();
    }
    else {
      $userView->userFormOk( $form );
      echo $form->getJsonOk();
    }
  }

  /**
   Action changeUserPassword()
  */
  public function changeUserPasswordForm() {

    $userView = new UserView();

    $form = $userView->actionChangeUserPasswordForm();
    if( $form->existErrors() ) {
      echo $form->getJsonError();
    }
    else {
      $userView->changeUserPasswordFormOk( $form );
      echo $form->getJsonOk();
    }
  }

  /**
   Action assign formRoles()
  */
  function assignaUserRolesForm() {

    $userView = new UserView();

    $form = $userView->actionUserRolesForm();
    if( $form->existErrors() ) {
      echo $form->getJsonError();
    }
    else {
      $userView->userRolesFormOk( $form );
      echo $form->getJsonOk();
    }
  }

}
