<?php
geozzy::load('model/ResourcetypeModel.php');



class ResourcetypeController {

  public function rTypeModuleRc( $rTypeClassName ) {
    $rTypeObj = new $rTypeClassName();
    self::addRType( $rTypeObj->name, $rTypeObj->nameLocations );
  }


  public function rExtModuleRc( $rExtClassName ) {
    $rExtObj = new $rExtClassName();
    self::createTaxonomies( $rExtObj->taxonomies );
  }


  public function addRType( $idName, $nameLocations ) {
    $rTypeData = array(
      'idName' => $idName,
      'relatedModels' => json_encode( self::getRtModels( $idName ) )
    );

    foreach( $nameLocations as $langKey => $name ) {
      $rTypeData[ 'name_'.$langKey ] = $name;
    }

    $newRType = new ResourcetypeModel( $rTypeData );
    $newRType->save();
  }


  public function createTaxonomies( $taxGroups ) {
    // echo "createTaxonomies\n"; print_r( $taxGroups );
    if( $taxGroups && is_array( $taxGroups ) && count( $taxGroups ) > 0 ) {
      foreach( $taxGroups as $tax ) {
        foreach( $tax['name'] as $langKey => $name ) {
          $tax['name_'.$langKey] = $name;
        }
        unset($tax['name']);
        $taxgroup = new TaxonomygroupModel( $tax );
        $taxgroup->save();
        if( isset($tax['initialTerms']) && count( $tax['initialTerms']) > 0 ) {
          foreach( $tax['initialTerms'] as $term ) {
            $term['taxgroup'] = $taxgroup->getter('id');

            foreach( $term['name'] as $langKey => $name ) {
               $term['name_'.$langKey] = $name;
            }
            unset($term['name']);
            $taxterm = new TaxonomytermModel( $term );
            $taxterm->save();
          }
        }
      }
    }
  }


  static public function getRtModels( $rTypeClassName ) {
    $retModels = array();

    if( class_exists( $rTypeClassName ) && property_exists( $rTypeClassName, 'rext' ) ) {
      $rTypeObj = new $rTypeClassName();
      if( is_array( $rTypeObj->rext ) && count( $rTypeObj->rext ) > 0 ) {
        foreach( $rTypeObj->rext as $rext ) {
          $retModels = array_merge( $retModels, self::getRextModels( $rext )  );
        }
      }
    }

    return $retModels;
  }


  static public function getRextModels( $rExtClassName ) {
    $retModels = array();

    if( class_exists( $rExtClassName ) && property_exists( $rExtClassName, 'models' ) ) {
      $rextObj = new $rExtClassName();
      if( is_array( $rextObj->models ) && count( $rextObj->models ) > 0 ) {
        $retModels = $rextObj->models ;
      }
    }

    return $retModels;
  }


  /*
  static public function addResourceTypes( $rtArray ) {
    if( count( $rtArray ) > 0 ) {
      foreach( $rtArray as $key => $rt ) {
        $rt['name'] = $rt['name']['es'];

        $rt['relatedModels'] = json_encode( self::getRtModels( $rt['idName'] ) );

        $rtO = new ResourcetypeModel( $rt );
        $rtO->save();
      }
    }
  }
  */

/*
  static public function getAllCategories( $rtArray ) {
    $returnCategories = array();

    if( count( $rtArray ) > 0 ) {
      foreach( $rtArray as $idName ) {
        $returnCategories = array_merge( $returnCategories, self::getRtCategories( $idName ) );
      }
    }

    return $returnCategories;
  }


  static public function getRtCategories( $rtClass ) {
    $retModels = array();

    echo "getRtCategories( $rtClass )\n";
    if( class_exists( $rtClass ) ) echo "class_exists\n";
    if( property_exists( $rtClass, 'rext' ) ) echo "property_exists\n";

    if( class_exists( $rtClass ) && property_exists( $rtClass, 'rext' ) ) {
      $rtObj = new $rtClass();
      if( is_array( $rtObj->rext ) && count( $rtObj->rext ) > 0 ) {
        foreach( $rtObj->rext as $rext ) {
          $retModels = array_merge( $retModels, self::getRextCategories( $rext ) );
        }
      }
    }

    return $retModels;
  }


  static public function getRextCategories( $rextClass ) {
    $retModels = array();

    echo "getRextCategories( $rextClass )\n";

    if( class_exists( $rextClass ) && property_exists( $rextClass, 'taxonomies' ) ) {
      $rextObj = new $rextClass();
      if( is_array( $rextObj->taxonomies ) && count($rextObj->taxonomies)>0 ) {
        $retModels = $rextObj->taxonomies ;
      }
    }

    return $retModels;
  }


  public function createRTypeTaxs( $rTypeIdName ) {
    // Crea todas as taxonomías de los rExt de un rType
    self::createTaxonomies( self::getRtCategories( $rTypeIdName ) );
  }
*/
}