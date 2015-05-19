<?php
admin::load('view/AdminViewMaster.php');


class AdminViewResourceOutTopic extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list resource
  **/
  public function listResourcesOutTopic($request) {

    $template = new Template( $this->baseDir );
    $template->assign('resourceouttopicTable', table::getTableHtml('AdminViewResourceOutTopic', '/admin/resourceouttopic/table/'.$request['1']) );
    $template->setTpl('listResourceOutTopic.tpl', 'admin');

    $this->template->assign( 'headTitle', __('Create and add resources') );
    $this->template->assign( 'headActions', '<button type="button" class="btn btn-default btn-outline"> '.__('Return').'</button>
                                             <select class="btn btn-primary assignResource" value="">
                                              <option id="0" value="0">'.__('Create').'</option>
                                             </select>
                                             <button type="button" class="btn btn-primary assignResource"> '.__('Assign selected').'</button>' );
    $this->template->assign( 'footerActions', '<button type="button" class="btn btn-default"> '.__('Return').'</button>
                                             <select class="btn btn-primary assignResource" value="">
                                              <option id="0" value="0">'.__('Create').'</option>
                                             </select>
                                             <button type="button" class="btn btn-primary assignResource"> '.__('Assign selected').'</button>' );    
    $this->template->addToBlock( 'col12', $template );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  public function listResourcesOutTopicTable($topicId) {

     table::autoIncludes();
    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    $tabla->setTabs(__('published'), array('*'=> __('All') ), '*');

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
    $tabla->setCol('published', __('Published'));
    
    // Filtrar por temática
    $tabla->setDefaultFilters( array('ResourceTopicModel.nottopic'=> $topicId[1] ) );
    $tabla->setAffectsDependences( array('ResourceTopicModel') ) ;
    $tabla->setJoinType('INNER');

      // Contido especial
    $tabla->colRule('published', '#1#', '<span class=\"rowMark rowOk\"><i class=\"fa fa-circle\"></i></span>');
    $tabla->colRule('published', '#0#', '<span class=\"rowMark rowNo\"><i class=\"fa fa-circle\"></i></span>');

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}

