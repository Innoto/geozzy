<?php
admin::load('view/AdminViewMaster.php');
Cogumelo::load("coreController/RequestController.php");

class AdminViewResourceInTopic extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
  * Section list resources in topic
  **/
  public function listResourcesInTopic($urlParams) {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('topic:list', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $validation = array('topic'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams,$validation);

    $topicId = $urlParamsList['topic'];

    $template = new Template( $this->baseDir );
    $template->assign('resourceintopicTable', table::getTableHtml('AdminViewResourceInTopic', '/admin/resourceintopic/table/topic/'.$topicId) );
    $template->setTpl('listResourceInTopic.tpl', 'admin');

    $topicmodel =  new TopicModel();
    $topic = $topicmodel->listItems(array("filters" => array("id" => $topicId)));
    $name = $topic->fetch()->getter('name', Cogumelo::getSetupValue( 'lang:default' ));


    $this->template->addToFragment( 'col12', $template );
    $this->template->assign( 'headTitle', $name );
    $assign = $useraccesscontrol->checkPermissions( array('topic:assign'), 'admin:full');
    if($assign){
      $this->template->assign( 'headActions', '<a href="/admin#resourceouttopic/list/topic/'.$topicId.'" class="btn btn-default"> '.__('Add resource').'</a>' );
      $this->template->assign( 'footerActions', '<a href="/admin#resourceouttopic/list/topic/'.$topicId.'" class="btn btn-default"> '.__('Add resource').'</a>' );
    }
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  public function listResourcesInTopicTable($urlParams) {
    $useraccesscontrol = new UserAccessController();
    $validation = array('topic'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams,$validation);

    $topicId = $urlParamsList['topic'];

    table::autoIncludes();
    $resource =  new ResourceTopicViewModel();
    $resourcetype =  new ResourcetypeModel();

    $resourcetypelist = $resourcetype->listItems( array( 'filters' => array( 'intopic' => $topicId ) ) )->fetchAll();

    $tiposArray = array();
    foreach ($resourcetypelist as $typeId => $type){
      $tiposArray[$typeId] = $typeId;
    }

    $tabla = new TableController( $resource );

    $tabla->setTabs('published', array('1'=>__('Published'), '0'=>__('Unpublished'), '*'=> __('All') ), '*');

    $filterByRtypeOpts = [];
    foreach ($resourcetypelist as $typeId => $type){
      $filterByRtypeOpts[$typeId] = $type->getter('name');
    }
    $filterByRtypeOpts['*'] = __('All');


    if(sizeof($filterByRtypeOpts)>2) {
      $tabla->setExtraFilter( 'rTypeId',  'combo', __('Resource type'), $filterByRtypeOpts, '*' );
    }
    //$tabla->setExtraFilter( 'times',  'combo', __('Published'), $filterByRtypeOpts, '*' );

    // set id search reference.
    $tabla->setSearchRefId('find');

      // set table Actions
    $publish = $useraccesscontrol->checkPermissions( array('resource:publish'), 'admin:full');
    if($publish){
      $tabla->setActionMethod(__('Publish'), 'changeStatusPublished', 'setPublishedStatus( $rowId, 1 )');
      $tabla->setActionMethod(__('Unpublish'), 'changeStatusUnpublished', 'setPublishedStatus( $rowId, 0 )');
    }
    $assign = $useraccesscontrol->checkPermissions( array('topic:assign'), 'admin:full');
    if($assign){
      $tabla->setActionMethod(__('Unasign'), 'unasign', 'deleteTopicRelation('.$topicId.', $rowId)');
    }



    $delete = $useraccesscontrol->checkPermissions( array('resource:delete'), 'admin:full');
    if($delete){
      $tabla->setActionMethod(__('Delete'), 'delete', 'deleteResource( $rowId)');
    }



    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#resource/edit/id/".$rowId."/topic/'.$topicId.'"');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'ID');
    $tabla->setColClasses('id', 'hidden-xs'); // hide id in mobile version
    $tabla->setCol('rTypeId', __('Type'));
    $tabla->setColClasses('rTypeId', 'hidden-xs'); // hide id rtype mobile version
    $tabla->setCol('title_'.Cogumelo::getSetupValue( 'lang:default' ), __('Title'));
    $tabla->setCol('published', __('Published'));

    // Filtrar por temática
    $userSession = $useraccesscontrol->getSessiondata();
    if($userSession && in_array('resource:mylist', $userSession['permissions'])){
      $filters = array( 'resourceTopicId'=> $topicId, 'inRtype'=>$tiposArray, 'user' => $userSession['data']['id'] );
    }else{
      $filters =  array('resourceTopicId'=> $topicId, 'inRtype'=>$tiposArray );
    }

    $tabla->setDefaultFilters($filters);
    //$tabla->setAffectsDependences( array('ResourceTopicModel') ) ;
    //$tabla->setJoinType('INNER');

    // Contido especial
    $tabla->colRule('published', '#1#', '<span class=\"rowMark rowOk\"><i class=\"fa fa-circle\"></i></span>');
    $tabla->colRule('published', '#0#', '<span class=\"rowMark rowNo\"><i class=\"fa fa-circle\"></i></span>');

    $typeModel =  new ResourcetypeModel();
    $typeList = $typeModel->listItems()->fetchAll();
    foreach ($typeList as $id => $type){
      $tabla->colRule('rTypeId', '#'.$id.'#', $type->getter('name'));
    }



    // Topic have taxonomy states
    $topicmodel =  new TopicModel();
    $topic = $topicmodel->listItems(array("filters" => array("id" => $topicId)))->fetch();

    if( $topic->getter('taxgroup') ) {

      $taxonomyterms = (new TaxonomytermModel())->listItems(array("filters" => array("taxgroup" =>  $topic->getter('taxgroup') )))->fetchAll();
      $taxonomygroup = (new TaxonomygroupModel())->listItems(array("filters" => array("id" =>  $topic->getter('taxgroup') )))->fetch();

      $taxonomygroupName = $taxonomygroup->getter('name', Cogumelo::getSetupValue( 'lang:default' ));

      $tabla->setCol('topicTaxonomyterm', $taxonomygroupName );
      $tabla->setColClasses('topicTaxonomyterm', 'hidden-sm'); // hide in medium screen version

      // Contido especial
      if( is_array($taxonomyterms) && count($taxonomyterms) > 0 ) {
        // separador
        $tabla->setActionSeparator();
        $filterStates = array();
        foreach($taxonomyterms as $term) {
          $tabla->colRule('topicTaxonomyterm', '#'.$term->getter('id').'#', $term->getter('name', Cogumelo::getSetupValue( 'lang:default' ) ));
          $tabla->setActionMethod(
            $taxonomygroupName. ' ('.$term->getter('name', Cogumelo::getSetupValue( 'lang:default' )). ')' ,
            'changeTaxonomytermTo'.$term->getter('idName'),
            'updateTopicTaxonomy( $rowId, '.$topicId.', '.$term->getter('id').')'
          );
          $filterStates[$term->getter('id')] = $term->getter('name', Cogumelo::getSetupValue( 'lang:default' ));
        }

        $filterStates['*'] = __('All');
        $tabla->setTabs('topicTaxonomyterm', $filterStates , '*');
      }

    }


    // Class must exist in configuration file: app/conf/inc/geozzyTopics.php
    require_once('conf/inc/geozzyTopics.php');
    if( class_exists('geozzyTopicsTableExtras') ) {
      // now, looking for method called with same name as topic idName
      $geozzyTopicsTableExtrasInstance =  new geozzyTopicsTableExtras();

      if( method_exists ( $geozzyTopicsTableExtrasInstance , $topic->getter('idName') ) ) {
        $table = $geozzyTopicsTableExtrasInstance->{ $topic->getter('idName') }( $urlParamsList, $tabla);
      }
    }




    // imprimimos o JSON da taboa
    $tabla->exec();
  }

}
