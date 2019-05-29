<?php

geozzy::load('view/RTypeViewCore.php');
user::autoIncludes();
table::autoIncludes();
rtypeStoryStep::autoincludes();

class RTypeStoryStepView extends RTypeViewCore implements RTypeViewInterface {

  public function __construct( $defResCtrl = null ) {
    // error_log( 'RTypeStoryStepView: __construct(): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    if( gettype( $defResCtrl ) !== 'object' ) {
      geozzy::load('controller/ResourceController.php');
      $defResCtrl = new ResourceController();
    }
    parent::__construct( $defResCtrl, new rtypeStoryStep() );
  }


  public function StoryStepList( $urlParams = false ) {
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $validation = array('story'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    $storyId = $urlParamsList['story'];

    $template = new Template();
    $template->assign('story',$storyId);
    $template->setTpl('storyStepList.tpl', 'rtypeStoryStep');

    $template->exec();
  }

  public function addStoryStep( $urlParams ) {

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('starred:list', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $validation = array('story'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    $story = $urlParamsList['story'];

    $template = new Template( $this->baseDir );

    $template->assign('stepsTable', table::getTableHtml( 'RTypeStoryStepView', '/admin/story/table/'.$story ) );
    $template->addClientScript('js/adminRtypeStoryStep.js', 'rtypeStoryStep');
    $template->setTpl('listSteps.tpl', 'rtypeStoryStep');

    $resourcetype =  new ResourcetypeModel();
    $resourcetypelist = $resourcetype->listItems( array( 'filters' => array( 'idName' => 'rtypeStoryStep' ) ) )->fetchAll();

    $resCreateByType = '<ul class="dropdown-menu dropdown-menu-right" role="menu">';
    foreach( $resourcetypelist as $i => $rType ) {
      $typeList[ $i ] = $rType->getter('name_'.Cogumelo::getSetupValue( 'lang:default' ));
      $resCreateByType .= '<li><a class="create-'.$rType->getter('idName').'" href="/admin#resource/create/story/'.$story.'/resourcetype/'.$rType->getter('id').'">'.$rType->getter('name_'.Cogumelo::getSetupValue( 'lang:default' )).'</a></li>';
    }
    $resCreateByType .= '</ul>';

    $this->template->assign( 'headTitle', __('Create and add resources') );
    $this->template->assign( 'headActions', '<a href="/admin#storysteps/'.$story.'" class="btn btn-default"> '.__('Return').'</a>
      <div class="btn-group assignResource RTypeStoryStepView">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>
      <div class="btn btn-primary assignResource btnAssign"> '.__('Add selected').'</div>'
    );

    $this->template->assign( 'footerActions', '<a href="/admin#storysteps/'.$story.'" class="btn btn-default"> '.__('Return').'</a>
      <div class="btn-group assignResource">
        <button type="button" class="btn btn-default dropdown-toggle btnCreate" data-toggle="dropdown" aria-expanded="false">
          '.__('Crear').' <span class="caret"></span>
        </button>
        '.$resCreateByType.'
      </div>
      <div class="btn btn-primary assignResource btnAssign"> '.__('Add selected').'</div>'
    );

    $this->template->addToFragment( 'col8', $template );

    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $panel = $this->getPanelBlock( '<ul style="list-style:none;"><li>'. __("Create a new resource") .'</li><li>'. __("Working with resource types") .'</li><li>'. __("Assign to this story") .'</li></ul>'. __('Assign resources: howto') );
    $this->template->addToFragment( 'col4', $panel );
    $this->template->exec();
  }

  public function listStepsTable( $story ) {

    $resource =  new ResourceModel();

    $tabla = new TableController( $resource );

    $tabla->setTabs(__('id'), array('*'=> __('All') ), '*');

    // set id search reference.
    $tabla->setSearchRefId('find');

     // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    $tabla->setEachRowUrl('"/admin#resource/edit/id/".$rowId');
    $tabla->setNewItemUrl('/admin#resource/create');

    // Nome das columnas
    $tabla->setCol('id', 'ID');
    //$tabla->setCol('rTypeId', __('Type'));
    $tabla->setCol('title_'.Cogumelo::getSetupValue( 'lang:default' ), __('Title'));


    /*  Relación historias - colecciones de pasos */
    $collection = new CollectionModel( );
    $resourceCollections = new ResourceCollectionsModel();

    // obtenemos las colección de pasos de una historia
    $resourceCollectionsList = $resourceCollections->listItems(
      array('filters' => array('resource' => $story['1'])) );


    // BUSCAMOS A COLECCIÓN
    if(isset($resourceCollectionsList)){
      $collectionId = false;
      while($resCol = $resourceCollectionsList->fetch()){
        $typecol = $collection->listItems(array('filters' => array('id' => $resCol->getter('collection'))))->fetch();
        if(isset($typecol)){
          if($typecol->getter('collectionType')==='steps'){
            $collectionId = $typecol->getter('id');
          }
        }
      }
    }


    // SE A COLECCIÓN NON EXISTE CRÉASE (pblanco 19/9/2016)
    if( $collectionId == false ) {
      $newCollection = new CollectionModel(['collectionType'=>'steps']);
      $newCollection->save();

      $newResourceCollections = new ResourceCollectionsModel( [ 'collection'=>$newCollection->getter('id'), 'resource'=> $story[1] ] );
      $newResourceCollections->save();

      $collectionId = $newCollection->getter('id');
    }


    $tabla->setActionMethod(__('Assign'), 'assign', 'createCollectionRelation('.$collectionId.',$rowId)');

    // Contido especial
    $typeModel =  new ResourcetypeModel();
    $rtype = $typeModel->listItems(array( 'filters' => array( 'idName' => 'rtypeStoryStep' ) ))->fetch();

    if (sizeof($collectionId)>0){
      // Filtrar por rtype y además que no esté asignado ya a esa colección de steps de esa story
      $tabla->setDefaultFilters( array('rTypeId'=> $rtype->getter('id'), 'notAsigned' => true) );
    }
    else{
      $tabla->setDefaultFilters( array('rTypeId'=> $rtype->getter('id')) );
    }

    // imprimimos o JSON da taboa
    $tabla->exec();
  }

  public function getPanelBlock( $content, $title = '', $icon = false ) {
    $template = new Template( $this->baseDir );

    if( is_string( $content ) ) {
      $template->assign( 'content', $content );
    }
    else {
      $template->setFragment( 'content', $content );
    }
    $template->assign( 'title', $title );
    $template->assign( 'icon', ( $icon ) ? $icon : 'fa-info' );
    $template->setTpl( 'adminPanel.tpl', 'admin' );

    return $template;
  }

} // class RTypePoiView
