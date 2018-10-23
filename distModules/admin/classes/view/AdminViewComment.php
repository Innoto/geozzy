<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyResourceView.php' );



class AdminViewComment extends AdminViewMaster {

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
    Section list user
   **/

  public function listComments() {
/*
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions();
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }
*/
    $template = new Template( $this->baseDir );
    $template->assign('commentTable', table::getTableHtml('AdminViewComment', '/admin/comment/table') );
    $template->setTpl('listComment.tpl', 'admin');

    $this->template->addToFragment( 'col12', $template );
    $this->template->assign( 'headTitle', __('Comments Management') );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }


  public function listCommentsTable() {

    table::autoIncludes();
    $commentModel =  new CommentModel();
    $tabla = new TableController( $commentModel );
    $tabla->setTabs('published', array('1'=>__('Published'), '0'=>__('Unpublished') ), '0');

    // set id search reference.
    $tabla->setSearchRefId('find');

    // set table Actions
    $tabla->setActionMethod(__('Publish'), 'changeStatusPublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>1 ))');
    $tabla->setActionMethod(__('Unpublish'), 'changeStatusUnpublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>0 ))');
    $tabla->setActionMethod(__('Delete'), 'delete', 'listitems(array("filters" => array("id" => $rowId)))->fetch()->delete()');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#comment/list"');
    $tabla->setNewItemUrl('/admin#comment/list');

    // Nome das columnas
    $tabla->setCol('id', 'ID');
    $tabla->setCol('resource', __('Resource'));
    $tabla->setCol('timeCreation', __('Time Creation'));
    $tabla->setCol('user', __('User'));
    $tabla->setCol('content', __('Comment'));
    $tabla->setCol('published', __('Published'));

    $taxtermModel =  new TaxonomytermModel();
    $typeTerm = $taxtermModel->listItems(array('filters' => array('idName' => 'comment')))->fetch();
    $filters =  array('type'=> $typeTerm->getter('id'));
    $tabla->setDefaultFilters($filters);

    //$tabla->setAffectsDependences( array('ResourceModel', 'UserModel') ) ;


    // Contido especial
    $tabla->colRule('published', '#1#', '<span class=\"rowMark rowOk\"><i class=\"fas fa-circle\"></i></span>');
    $tabla->colRule('published', '#0#', '<span class=\"rowMark rowNo\"><i class=\"fas fa-circle\"></i></span>');
/*
    $resourceModel =  new ResourceModel();
    $resources = $resourceModel->listItems()->fetchAll();
    foreach( $resources as $id => $res ) {
      $tabla->colRule('resource', '#'.$id.'#', $res->getter('title'));
    }
*/
    // imprimimos o JSON da taboa
    $tabla->exec();
  }



  public function listSuggestions() {
/*
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions();
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }
*/
    $template = new Template( $this->baseDir );
    $template->assign('suggestionTable', table::getTableHtml('AdminViewComment', '/admin/suggestion/table') );
    $template->setTpl('listSuggestion.tpl', 'admin');

    $this->template->addToFragment( 'col12', $template );
    $this->template->assign( 'headTitle', __('Suggestions Management') );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }


  public function listSuggestionsTable() {

    table::autoIncludes();
    $commentModel =  new CommentModel();
    $tabla = new TableController( $commentModel );
    $tabla->setTabs( 'published' , array('1'=>__('Published'), '0'=>__('Unpublished'), '*'=> __('All') ), '*');

    // set id search reference.
    $tabla->setSearchRefId('find');

    // set table Actions
    $tabla->setActionMethod(__('Publish'), 'changeStatusPublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>1 ))');
    $tabla->setActionMethod(__('Unpublish'), 'changeStatusUnpublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>0 ))');
    $tabla->setActionMethod(__('Delete'), 'delete', 'listitems(array("filters" => array("id" => $rowId)))->fetch()->delete()');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#suggestion/list"');
    $tabla->setNewItemUrl('/admin#suggestion/list');


    // Nome das columnas
    $tabla->setCol('id', 'ID');
    $tabla->setCol('resource', __('Resource'));
    $tabla->setCol('timeCreation', __('Time Creation'));
    $tabla->setCol('user', __('User'));
    $tabla->setCol('content', __('Comment'));
    $tabla->setCol('published', __('Published'));

    $taxtermModel =  new TaxonomytermModel();
    $typeTerm = $taxtermModel->listItems(array('filters' => array('idName' => 'suggest')))->fetch();
    $filters =  array('type'=> $typeTerm->getter('id'));
    $tabla->setDefaultFilters($filters);
    // Contido especial
    $tabla->colRule('published', '#1#', '<span class=\"rowMark rowOk\"><i class=\"fas fa-circle\"></i></span>');
    $tabla->colRule('published', '#0#', '<span class=\"rowMark rowNo\"><i class=\"fas fa-circle\"></i></span>');
/*
    $resourceModel =  new ResourceModel();
    $resources = $resourceModel->listItems()->fetchAll();
    foreach( $resources as $id => $res ) {
      $tabla->colRule('resource', '#'.$id.'#', $res->getter('title'));
    }
*/
    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}
