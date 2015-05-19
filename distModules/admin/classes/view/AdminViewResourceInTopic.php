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

    $this->template->addToBlock( 'col12', $template );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  public function listResourcesInTopicTable($topic) {

    table::autoIncludes();
    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

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

    // Filtrar por temática
    $tabla->setDefaultFilters( array('ResourceTopicModel.topic'=> $topic[1] ) );
    $tabla->setAffectsDependences( array('ResourceTopicModel') ) ;
    $tabla->setJoinType('INNER');

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}

