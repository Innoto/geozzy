<?php
admin::load('view/AdminViewMaster.php');


class AdminViewTaxonomy extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  /**
  * Section list user
  **/

  function listTaxTerm( $request ) {

    table::autoIncludes();
    $this->template->assign('taxTermTable', table::getTableHtml('AdminViewTaxonomy', '/admin/taxonomygroup/'.$request[1].'/table') );
    $this->template->setTpl('listTaxTerm.tpl', 'admin');
    $this->commonAdminInterface();

  }

  function listTaxTermTable( $request ){

    //$filter = array( 'group' => $request[1]);
    Cogumelo::console($request);


    table::autoIncludes();
    $taxtermModel =  new TaxonomytermModel();

    $tabla = new TableController( $taxtermModel );

    //Temporal mientras sea obligatorio.
    $tabla->setTabs('idName', array( '*'=> 'Todos' ), '*');

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin/taxonomygroup/term/edit/".$rowId');
    $tabla->setNewItemUrl('/admin/taxonomygroup/term/create');

    // Nome das columnas
    $tabla->setCol('id', 'Id');
    $tabla->setCol('idName', 'Name');
    $tabla->setCol('group', 'Group');

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

  /**
  * Section create role
  **/

  function createTaxTerm() {
    /*
    $roleView = new RoleView();

    $form = $roleView->roleFormDefine();
    $form->setAction('/admin/role/sendrole');
    $form->setSuccess( 'redirect', '/admin/role/list' );

    $createRoleHtml = $roleView->roleFormGet( $form );
    $this->template->assign('createRoleHtml', $createRoleHtml);
    $this->template->setTpl('createRole.tpl', 'admin');

    $this->commonAdminInterface();
    */
  }

  /**
  * Section edit role
  **/

  function editTaxTerm($request) {
    /*
    $roleView = new RoleView();

    $form = $roleView->roleUpdateFormDefine($request);
    $form->setAction('/admin/role/sendrole');
    $form->setSuccess( 'redirect', '/admin/role/list' );
    $editRoleHtml = $roleView->roleFormGet( $form );
    $this->template->assign('editRoleHtml', $editRoleHtml);

    $this->template->setTpl('editRole.tpl', 'admin');
    $this->commonAdminInterface();
    */
  }


  /**
   Action roleForm
  */
  /*function sendRoleForm() {

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
  */

}

