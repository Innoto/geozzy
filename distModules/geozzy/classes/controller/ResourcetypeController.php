<?php


geozzy::load('model/ResourcetypeModel.php');

class ResourcetypeController {


	static function addResourceTypes( $rtArray ) {


    if( count( $rtArray ) > 0 ) {
      foreach( $rtArray as $key => $rt ) {
        $rt['name'] = $rt['name']['es'];

        $rt['relatedModels'] = json_encode( self::getRtModels( $rt['idName'] ) );

        $rtO = new ResourcetypeModel( $rt );
        $rtO->save();
      }
    }
  }

  static function getRtModels( $rtClass ) {
    $retModels = array();

    if( class_exists( $rtClass )  && property_exists($rtClass, 'rext' ) ) {
      $rtObj = new $rtClass();
      if( is_array( $rtObj->rext ) && sizeof($rtObj->rext)>0 ) {
        foreach ( $rtObj->rext as $rext) {
          $retModels = array_merge( $retModels,  self::getRextModels( $rext )  );
        }
      }
    }

    return $retModels;
  }

  static function getRextModels( $rextClass ) {

    $retModels = array();

    if( class_exists( $rextClass )  && property_exists($rextClass, 'models' ) ) {
      $rextObj = new $rextClass();

      if( is_array( $rextObj->models ) && sizeof($rextObj->models)>0 ) {
        $retModels = $rextObj->models ;
      }

    }


    return $retModels;
  }
}