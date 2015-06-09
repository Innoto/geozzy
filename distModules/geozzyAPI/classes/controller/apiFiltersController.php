<?php


class apiFiltersController {

	static function resourceListOptions( $param ) {
		geozzy::load('model/ResourceModel.php');
		$rModel = new ResourceModel();

    // extract parameters
    $params = explode('/', $param[1]);


    $options = array( );

    //
    // Resource type
    //$options['affectsDependences'] = false;

    if( $params[5] == 'rtype' && $params[6] != 'false') {
      $options['affectsDependences'] = $rModel->dependencesByResourcetype( $params[6] ) ;

    }

    //
    // fields
    if( $params[1] == 'fields' && $params[2] != 'false' ) {

      $options['fields'] = self::clearFields( explode(',', urldecode( $params[2] ) ) );
    }

    //
    // fields and fieldvalues
    if(
      ( $params[3] == 'filters' && $params[4] != 'false' ) &&
      ( $params[3] == 'filtervalues' && $params[4] != 'false' )
    ) {
      $options['filters'] = array('id' => 10);
    }

    // filters
    return $options;
	}


  static function clearFilters() {

  }

  static function clearFields( $fields ) {
    geozzy::load('model/ResourceModel.php');
    $rModel = new ResourceModel();
    $voKeys = $rModel->getCols( true );

    $retKeys = array();
    foreach( $fields  as $kkey => $kVal ) {

      if( in_array( $kVal, array_keys( $voKeys ) ) ) {

        $retKeys[] = $kVal;
      }
    }

    return $retKeys;

  }



}
