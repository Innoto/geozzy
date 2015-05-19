<?php
admin::load('view/AdminViewMaster.php');


class AdminViewResourceInTopic extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list resources in topic
  **/
  public function listResourcesInTopic($request) {

    $template = new Template( $this->baseDir );
    $template->assign('resourceintopicTable', table::getTableHtml('AdminViewResourceInTopic', '/admin/resourceintopic/table/'.$request['1']) );
    $template->setTpl('listResourceInTopic.tpl', 'admin');

    $topicmodel =  new TopicModel();
    $topic = $topicmodel->listItems(array("filters" => array("id" => $request['1'])));
    $name = $topic->fetch()->getter('name', LANG_DEFAULT);

    $this->template->addToBlock( 'col12', $template );
    $this->template->assign( 'headTitle', $name );
    $this->template->assign( 'headActions', '<button type="button" class="newTaxTerm btn btn-default btn-outline"> '.__('Add resource').'</button>' );
    $this->template->assign( 'footerActions', '<button class="btn btn-primary saveTerms"> '.__('Add resource').'</button>' );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  public function listResourcesInTopicTable($topicId) {

    table::autoIncludes();
    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

      // set table Actions
    $tabla->setActionMethod(__('Publish'), 'changeStatusPublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>1 ))');
    $tabla->setActionMethod(__('Unpublish'), 'changeStatusUnpublished', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "published", "changeValue"=>0 ))');
    $tabla->setActionMethod(__('Delete'), 'delete', 'listitems(array("filters" => array("id" => $rowId)))->fetch()->delete()');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#resource/edit/".$rowId');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'ID');
    $tabla->setCol('type', __('Type'));
    $tabla->setCol('title_'.LANG_DEFAULT, __('Title'));
    $tabla->setCol('published', __('Published'));

    // Filtrar por temática
    $tabla->setDefaultFilters( array('ResourceTopicModel.topic'=> $topicId[1] ) );
    $tabla->setAffectsDependences( array('ResourceTopicModel') ) ;
    $tabla->setJoinType('INNER');

      // Contido especial
    $tabla->colRule('published', '#1#', '<span class=\"rowMark rowOk\"><i class=\"fa fa-circle\"></i></span>');
    $tabla->colRule('published', '#0#', '<span class=\"rowMark rowNo\"><i class=\"fa fa-circle\"></i></span>');

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}

