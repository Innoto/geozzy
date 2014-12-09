<?php
admin::load('view/AdminViewMaster.php');


class AdminViewUser extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  function editUser() {
    $this->template->setTpl('editUser.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);

  }

  function showUser() {
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();

    /*** Temporalmente mientras no funcionan las relaciones ***/
    $filedataControl = new FiledataController();
    $filedataVO = $filedataControl->find($user->getter('id'));
    $user->setter('avatar', $filedataVO);
    /*******/

    $this->template->assign( 'user' , $user);
    $this->template->setTpl('showUser.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);

  }
  function createUser() {
    $this->template->setTpl('createUser.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);

  }

  function listUsers() {

    table::autoIncludes();
    $this->template->assign('userTable', table::getTableHtml('AdminViewUser', '/admin/user/table') );
    $this->template->setTpl('listUser.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);
  }

  function listUsersTable(){

    table::autoIncludes();
    $userControl =  new UserController();

    $tabla = new TableController( $userControl );

    $tabla->setTabs('role', array('10'=>'SuperAdmin', '11'=>'User', '*'=> 'Todos' ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set table Actions
    /*$tabla->setActionMethod('Borrar', 'delete', 'deleteFromId($rowId)');
    $tabla->setActionMethod('Mover a Lla Coruña', 'moveToAcoruna', 'updateFromArray( array($primaryKey=>$rowId,  "lostProvince"=>1) )');
    $tabla->setActionMethod('Mover a Lugo', 'moveToLugo', 'updateFromArray( array($primaryKey=>$rowId,  "lostProvince"=>2) )');
*/
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
    $tabla->setCol('role', 'Role');

    // establecer reglas a campo concreto con expresions regulares
    $tabla->colRule('role', '#10#', 'SuperAdmin');
    $tabla->colRule('role', '#11#', 'User');

    // imprimimos o JSON da taboa
    $tabla->exec();



  }


}

