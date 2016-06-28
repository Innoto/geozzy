<?php


class RoutesController {

  function __construct() {



  }



  function extractPoints( $geom ) {
    $points = array();

    foreach( $geom->getComponents() as $comp ) {
      if( $comp->getGeomType() == 'LineString' ){
        //$points = array_merge( $points , $this->extractPoints( $comp ) );
      }
      else
      if( $comp->getGeomType() == 'Point' ) {

        if( $comp->z() ) {
          $points[] = [ $comp->y(), $comp->x(), $comp->z() ];
        }
        else {
          $points[] = [ $comp->y(), $comp->x(), false ];
        }

        //array_push( $points , get_class_methods($comp) );
        //$points[] = [$comp->getX() , $comp->getY()];
      }
    }

    return $points;
  }


  public function getRoute( $ids ) {
    rextRoutes::autoIncludes();
    $useraccesscontrol = new UserAccessController();

    $route = false;

    $f = array();
    $f['ResourceModel.id'] = $ids;


    if(! ($useraccesscontrol->checkPermissions('resource:create', 'admin:full') || $useraccesscontrol->checkPermissions('resource:edit', 'admin:full')) ) {
      $f['ResourceModel.published'] = 1;
    }

//
//var_dump(array('affectsDependences'=> array('FiledataModel') , 'filters' => $f ))

    $routesModel = new RoutesModel();
    $routesList = $routesModel->listItems( array('joinType'=>'RIGHT','affectsDependences'=> array('ResourceModel') , 'filters' => $f ));

    $routes = [];

    while( $routeVO = $routesList->fetch() ) {

      $route = [ ];

      if(  $routeVO->getter('routeFile') ) {

        $fileDataList = (new FiledataModel(['id'=> $routeVO->getter('routeFile') ]) )->save();
        $filePath = cogumeloGetSetupValue( 'mod:filedata' )['filePath'] . $fileDataList->getter('absLocation');
      }
      else {
        $filePath = false;
      }


      if ( file_exists( $filePath ) ) {

        $fnSplited = explode( '.', $filePath );
        /*array_pop( $fnSplited )*/

        try {
          $polygon = geoPHP::load( file_get_contents($filePath) , array_pop( $fnSplited ) );
          /*echo "<pre>";
          var_dump( $polygon->getGeomType() );
          echo "<br>--------------------<br>";
          var_dump( json_encode( $this->extractPoints( $polygon )) );*/
          $cent = $polygon->getCentroid();

          $route['id'] =  $routeVO->getter('resource');
          $route['circular'] = $routeVO->getter('circular');
          $route['centroid'] =  [ $cent->y(), $cent->x() ];
          $route['trackPoints'] = $this->extractPoints( $polygon );
        }
        catch(Exception $e) {
            Cogumelo::error( $e->getMessage() );
        }
      }
      else {
        Cogumelo::log('Route file not found: '. $filePath);
      }

      $routes[] = $route;

    }


    return $routes;
  }



  public function validateRoute( $filePath ) {
    rextRoutes::autoIncludes();

    $isValid = false;
    //$filePath = '/home/pblanco/Descargas/Felechosa_final.gpx';

    if ( file_exists( $filePath ) ) {

      $fnSplited = explode( '.', $filePath );
      /*array_pop( $fnSplited )*/

      try {
        $polygon = geoPHP::load( file_get_contents($filePath) , array_pop( $fnSplited ) );
        /*echo "<pre>";
        var_dump( $polygon->getGeomType() );
        echo "<br>--------------------<br>";
        var_dump( json_encode( $this->extractPoints( $polygon )) );*/
        $isValid = true;
      }
      catch(Exception $e) {
          Cogumelo::error( $e->getMessage() );
      }
    }
    else {
      Cogumelo::log('Route file not found: '. $filePath);
    }


    return $route;
  }

}
