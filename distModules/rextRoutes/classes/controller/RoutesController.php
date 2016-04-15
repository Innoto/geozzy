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


  public function getRoute( $filePath, $id ) {
    rextRoutes::autoIncludes();

    $route = false;
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
        $cent = $polygon->getCentroid();

        $route = [];
        $route['id'] =  $id;
        $route['centroid'] =  [ $cent->y(), $cent->x() ];
        $route['trackPoints'] = $this->extractPoints( $polygon );
      }
      catch(Exception $e) {
          Cogumelo::error( $e->getMessage() );
      }
    }
    else {
      Cogumelo::error('File not found: '. $filePath);
    }


    return $route;
  }

}
