<?php
admin::load('view/AdminViewMaster.php');


class AdminViewUser extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  /**
  * Section user profile
  **/

  function showUser() {
    $this->template->setTpl('showUser.tpl', 'admin');
    $this->commonAdminInterface();

  }

  /**
  * Section list user
  **/

  function listUsers() {

    table::autoIncludes();
    $this->template->assign('userTable', table::getTableHtml('AdminViewUser', '/admin/user/table') );
    $this->template->setTpl('listUser.tpl', 'admin');
    $this->commonAdminInterface();
  }

  function listUsersTable(){

    table::autoIncludes();
    $user =  new UserModel();

    $tabla = new TableController( $user );

    $tabla->setTabs('active', array('1'=>'Activos', '0'=>'Bloqueados', '*'=> 'Todos' ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set table Actions
    $tabla->setActionMethod('Activar', 'changeStatusActive', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "active", "changeValue"=>1 ))');
    $tabla->setActionMethod('Bloquear', 'changeStatusLock', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "active", "changeValue"=>0 ))');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin/user/edit/".$rowId');
    $tabla->setNewItemUrl('/admin/user/create');

    // Nome das columnas
    $tabla->setCol('id', 'Id');
    $tabla->setCol('login', 'Login');
    $tabla->setCol('name', 'Name');
    $tabla->setCol('surname', 'Surname');
    $tabla->setCol('email', 'Email');
    //$tabla->setCol('role', 'Role');

    // establecer reglas a campo concreto con expresions regulares
    //$tabla->colRule('role', '#10#', 'SuperAdmin');
    //$tabla->colRule('role', '#11#', 'User');

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

  /**
  * Section create user
  **/

  function createUser() {

    $userView = new UserView();

    $form = $userView->userFormDefine();
    $form->setAction('/admin/user/senduser');
    $form->setSuccess( 'redirect', '/admin/user/list' );

    $createUserHtml = $userView->userFormGet( $form );
    $this->template->assign('createUserHtml', $createUserHtml);
    $this->template->setTpl('createUser.tpl', 'admin');

    $this->commonAdminInterface();

  }

  /**
  * Section edit user
  **/

  function editUser($request) {

    $userView = new UserView();

    /*FORM EDIT*/
    $form = $userView->userUpdateFormDefine($request);
    $form->setAction('/admin/user/senduser');
    $form->setSuccess( 'redirect', '/admin/user/list' );
    $editUserHtml = $userView->userFormGet( $form );
    $this->template->assign('editUserHtml', $editUserHtml);
    /*--------------------*/

    /*FORM CHANGE PASSWORD*/
    $formChange = $userView->userChangePasswordFormDefine($request);
    $formChange->setAction('/admin/user/changepassword');
    $formChange->setSuccess( 'redirect', '/admin/user/list' );
    $changePasswordHtml = $userView->userChangePasswordFormGet( $formChange );
    $this->template->assign('changePasswordHtml', $changePasswordHtml);

    /*--------------------*/

    $this->template->setTpl('editUser.tpl', 'admin');
    $this->commonAdminInterface();

  }


  /**
   Action userForm
  */
  function sendUserForm() {

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
  function changeUserPasswordForm() {

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

}

