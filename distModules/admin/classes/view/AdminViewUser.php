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
    $this->template->assign( 'user' , $user);

    $this->template->setTpl('showUser.tpl', 'admin');
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
    $tabla->setActionMethod('Activar', 'changeStatusActive', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "active", "changeValue"=>1 ))');
    $tabla->setActionMethod('Bloquear', 'changeStatusLock', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "active", "changeValue"=> 0 ))');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#user/edit/".$rowId');
    $tabla->setNewItemUrl('/admin#user/create');

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

  public function createUser() {

    $userView = new UserView();

    $form = $userView->userFormDefine();
    $form->setAction('/admin/user/senduser');
    $form->setSuccess( 'redirect', '/admin#user/list' );

    $createUserHtml = $userView->userFormGet( $form );
    $this->template->assign('createUserHtml', $createUserHtml);
    $this->template->setTpl('createUser.tpl', 'admin');

    $this->template->exec();

  }

  /**
  * Section edit user
  **/
  public function editUser( $request ) {

    $userView = new UserView();

    /*FORM EDIT*/
    $form = $userView->userUpdateFormDefine($request);
    $form->setAction('/admin/user/senduser');
    $form->setSuccess( 'redirect', '/admin#user/list' );
    $editUserHtml = $userView->userFormGet( $form );
    $this->template->assign('editUserHtml', $editUserHtml);
    /*--------------------*/

    /*FORM CHANGE PASSWORD*/
    $formChange = $userView->userChangePasswordFormDefine($request);
    $formChange->setAction('/admin/user/changepassword');
    $formChange->setSuccess( 'redirect', '/admin#user/list' );
    $changePasswordHtml = $userView->userChangePasswordFormGet( $formChange );
    $this->template->assign('changePasswordHtml', $changePasswordHtml);
    /*--------------------*/

    /*FORM ASSIGN ROLES*/
    $userRolesForm = $userView->userRolesFormDefine($request);
    $userRolesForm->setAction('/admin/user/assignroles');
    $userRolesForm->setSuccess( 'redirect', '/admin#user/list' );
    $userRolesFormHtml = $userView->userRolesFormGet( $userRolesForm );
    $this->template->assign('userRolesFormHtml', $userRolesFormHtml);
    /*--------------------*/

    $this->template->setTpl('editUser.tpl', 'admin');

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
