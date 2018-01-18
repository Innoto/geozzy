<?php
geozzy::load( 'controller/RExtController.php' );

class RExtTravelPlannerController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl = false ) {
    // Este RExt funciona tambien como modulo "autonomo"
    if( $defRTypeCtrl !== false ) {
      parent::__construct( $defRTypeCtrl, new rextTravelPlanner(), 'rExtTravelPlanner_' );
    }
  }


  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();
    // error_log( 'USER: '.print_r( $userInfo, true ) );
    $userId = isset( $userInfo['data']['id'] ) ? $userInfo['data']['id'] : false;


    return $rExtData;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {
  }


  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
  }



  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo( $resId = false ) {

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    $resId = $this->defResCtrl->resObj->getter('id');

    $rExtViewBlockInfo['template']['full'] = new Template();
    $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
    $rExtViewBlockInfo['template']['full']->assign( 'resId', $resId );
    $rExtViewBlockInfo['template']['full']->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
    $rExtViewBlockInfo['template']['full']->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');
    $rExtViewBlockInfo['template']['full']->addClientScript( 'js/model/ResourceModel.js' , 'geozzy');
    $rExtViewBlockInfo['template']['full']->addClientScript( 'js/collection/ResourceCollection.js' , 'geozzy');
    $rExtViewBlockInfo['template']['full']->addClientScript( 'js/model/ResourcetypeModel.js' , 'geozzy');
    $rExtViewBlockInfo['template']['full']->addClientScript( 'js/collection/ResourcetypeCollection.js' , 'geozzy');
    $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextTravelPlanner' );

    return $rExtViewBlockInfo;
  }




  public function newTravelPlannerStructure( $user ) {
    // Hay que crear toda la estructura: resource rtypeTravelPlanner

    // Creamos el recurso de TravelPlanner
    $tpsResInfo = array(
      'rTypeId' => $this->getTravelPlannerRTypeId(),
      'user' => $user,
      'title' => 'Travel Planner. user '.$user,
      'title_'.Cogumelo::getSetupValue( 'lang:default' ) => 'Travel Planner. user '.$user,
      'published' => true,
      'timeCreation' => gmdate( 'Y-m-d H:i:s', time() )
    );
    $resModel = new ResourceModel( $tpsResInfo );
    $resModel->setterDependence( 'id', new TravelPlannerModel() );
    $resModel->save(['affectsDependences'=>true]);
    $resMainId = $resModel->getter('id');

    // Creamos las URLs del recurso de favoritos
    geozzy::load( 'controller/ResourceController.php' );
    $resCtrl = new ResourceController();
    $allLang = Cogumelo::getSetupValue( 'lang:available' );
    foreach( $allLang as $langKey => $langInfo ) {
      $tpsUrl = $resCtrl->getUrlByPattern( $resMainId, $langKey, $resMainId );
      $resCtrl->setUrl( $resMainId, $langKey, $tpsUrl );
    }

    return( array( 'tpsId' => $resMainId ) );
  }



  public function getTravelPlannerRTypeId() {
    $rTypeModel = new ResourcetypeModel();
    $rTypeList = $rTypeModel->listItems( array( 'filters' => array( 'idName' => 'rtypeTravelPlanner' ), 'cache' => $this->cacheQuery ) );
    $rTypeObj = ( $rTypeList ) ? $rTypeList->fetch() : false;
    $rTypeId = ( $rTypeObj ) ? $rTypeObj->getter( 'id' ) : false;

    return( $rTypeId );
  }


  public function getTravelPlannerUrl( $user ) {


    $tpUrl = false;


    $tpModel = new ResourceModel();
    $tpList = $tpModel->listItems( array( 'filters' => array( 'user' => $user, 'rTypeId' => $this->getTravelPlannerRTypeId() ), 'cache' => $this->cacheQuery ) );
    $tpObj = ( $tpList ) ? $tpList->fetch() : false;
    if( $tpObj ) {
      $tpsId = $tpObj->getter('id');
    }
    else {
      $tpStructure = $this->newTravelPlannerStructure( $user );
      $tpsId = $tpStructure['tpsId'];
    }
    geozzy::load( 'controller/ResourceController.php' );
    $resCtrl = new ResourceController();

    $tpUrl = $resCtrl->getUrlAlias( $tpsId );

    return $tpUrl;
  }



  public function getTravelPlanner( $user ) {
    $res = false;


    $tpModel = new ResourceModel();
    $tpList = $tpModel->listItems(
      array(
        'filters' => array( 'user' => $user, 'rTypeId' => $this->getTravelPlannerRTypeId() ),
        'affectsDependences' => ['TravelPlannerModel'],
        'cache' => $this->cacheQuery
      )
    );

    $tpObj = ( $tpList ) ? $tpList->fetch() : false;
    if( $tpObj ) {
      //$tpObj->setterDependence('id', (new TravelPlannerModel())->listItems(['filter'=>['resource'=>$tpObj->getter('id')], 'cache' => $this->cacheQuery])->fetch() );
      $res = $tpObj;
    }

    return $res;
  }


  public function setTravelPlanner( $data ) {
    $res = false;

    $tpModel = new ResourceModel();
    $tpList = $tpModel->listItems(
      array(
        'filters' => array( 'id'=> $data['id']),
        'affectsDependences' => ['TravelPlannerModel'],
        'cache' => $this->cacheQuery
      )
    );

    $tpObj = ( $tpList ) ? $tpList->fetch() : false;
    if( $tpObj ) {

      //$dep = (new TravelPlannerModel())->listItems(['filter'=>['resource'=> $tpObj->getter('id')], 'cache' => $this->cacheQuery])->fetch();

      $cleanlist = [];
      if( isset($data['list']) && sizeof($data['list'])>0  )
      foreach( $data['list'] as $l ) {
        $cleanlist[] = $l;
      }

      $dep = $tpObj->getterDependence('id', 'TravelPlannerModel')[0];
      $dep->setter('travelPlannerJson', json_encode($cleanlist) );
      $dep->setter('checkIn', $data['checkin'] );
      $dep->setter('checkOut', $data['checkout'] );

      $tpObj->setterDependence('id', $dep );


      $tpObj->save(['affectsDependences'=>true]);
      //$res = $tpObj;

      $res = $this->getTravelPlanner( $tpObj->getter('user') );
    }

    return $res;
  }


} // class RExtFavouriteController
