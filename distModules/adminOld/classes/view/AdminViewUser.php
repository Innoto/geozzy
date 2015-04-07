<?php
adminOld::load('view/AdminViewMaster.php');


class AdminViewUser extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  /**
  * Section user profile
  **/

  function showUser() {
    $this->template->setTpl('showUser.tpl', 'adminOld');
    $this->commonAdminInterface();

  }

  /**
  * Section list user
  **/

  function listUsers() {

    table::autoIncludes();
    $this->template->assign('userTable', table::getTableHtml('AdminViewUser', '/adminOld/user/table') );
    $this->template->setTpl('listUser.tpl', 'adminOld');
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
    $tabla->setActionMethod('Bloquear', 'changeStatusLock', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "active", "changeValue"=> 0 ))');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/adminOld/user/edit/".$rowId');
    $tabla->setNewItemUrl('/adminOld/user/create');

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
    $form->setAction('/adminOld/user/senduser');
    $form->setSuccess( 'redirect', '/adminOld/user/list' );

    $createUserHtml = $userView->userFormGet( $form );
    $this->template->assign('createUserHtml', $createUserHtml);
    $this->template->setTpl('createUser.tpl', 'adminOld');

    $this->commonAdminInterface();

  }

  /**
  * Section edit user
  **/

  function editUser($request) {

    $userView = new UserView();

    /*FORM EDIT*/
    $form = $userView->userUpdateFormDefine($request);
    $form->setAction('/adminOld/user/senduser');
    $form->setSuccess( 'redirect', '/adminOld/user/list' );
    $editUserHtml = $userView->userFormGet( $form );
    $this->template->assign('editUserHtml', $editUserHtml);
    /*--------------------*/

    /*FORM CHANGE PASSWORD*/
    $formChange = $userView->userChangePasswordFormDefine($request);
    $formChange->setAction('/adminOld/user/changepassword');
    $formChange->setSuccess( 'redirect', '/adminOld/user/list' );
    $changePasswordHtml = $userView->userChangePasswordFormGet( $formChange );
    $this->template->assign('changePasswordHtml', $changePasswordHtml);
    /*--------------------*/

    /*FORM ASSIGN ROLES*/
    $userRolesForm = $userView->userRolesFormDefine($request);
    $userRolesForm->setAction('/adminOld/user/assignroles');
    $userRolesForm->setSuccess( 'redirect', '/adminOld/user/list' );
    $userRolesFormHtml = $userView->userRolesFormGet( $userRolesForm );
    $this->template->assign('userRolesFormHtml', $userRolesFormHtml);
    /*--------------------*/

    $this->template->setTpl('editUser.tpl', 'adminOld');
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

