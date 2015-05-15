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
  public function listResourcesInTopic() {

    $template = new Template( $this->baseDir );
    $template->assign('resourceintopicTable', table::getTableHtml('AdminViewResourceInTopic', '/admin/resourceintopic/table') );
    $template->setTpl('listResourceInTopic.tpl', 'admin');

    $this->template->addToBlock( 'col12', $template );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  public function listResourcesInTopicTable() {

    table::autoIncludes();
    $resourceintopic =  new ResourceModel();

    $tabla = new TableController( $resourceintopic );

    $tabla->setTabs(__('asigned'), array('1'=>__('Asigned'), '0'=>__('Unasigned'), '*'=> __('All') ), '*');

    // set query filters
    //$internalFilters['topic'] = $resourceintopic::$extraFilters['topic'];
    //$tabla->setInternalFilters($internalFilters);

    // set id search reference.
    $tabla->setSearchRefId('tableSearch');

    // set table Actions
    $tabla->setActionMethod(__('Unasign'), 'changeStatusUnasigned', 'updateKey( array( "searchKey" => "id", "searchValue" => $rowId, "changeKey" => "asigned", "changeValue"=>0 ))');

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // Nome das columnas
    $tabla->setCol('id', 'Id');
    $tabla->setCol('type', __('Type'));
    $tabla->setCol('title', __('Title'));
    $tabla->setCol('user', __('User'));

        // Contido especial: falta saber se imos ter a clave de temática no recurso ou imos ter q ir buscala a táboa da relación
    $tabla->colRule('asigned', '#1#', '<span class=\"rowMark rowOk\"><i class=\"fa fa-circle\"></i></span>');
    $tabla->colRule('asigned', '#0#', '<span class=\"rowMark rowNo\"><i class=\"fa fa-circle\"></i></span>');

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}

