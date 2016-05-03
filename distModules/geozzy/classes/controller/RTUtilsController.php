<?php
geozzy::load('model/ResourcetypeModel.php');



class RTUtilsController {

  var $moduleClass = false;

  public function __construct( $moduleClass ) {
    $this->moduleClass = $moduleClass;
  }

  public function rTypeModuleRc() {
    $rTypeObj = new $this->moduleClass();
    $rTypeModel = $this->addRType( $rTypeObj->name, $rTypeObj->nameLocations );

    if( $rTypeModel ) {
      $this->addRTypeToTopics( $rTypeModel->getter( 'id' ), $rTypeModel->getter( 'idName' ) );
    }
  }


  public function rExtModuleRc() {
    $rExtObj = new $this->moduleClass();
    $this->createTaxonomies( $rExtObj->taxonomies );
  }


  public function addRType( $rTypeIdName, $nameLocations ) {
    $rTypeModel = false;

    $rTypeData = array(
      'idName' => $rTypeIdName,
      'relatedModels' => json_encode( $this->getRtModels( $rTypeIdName ) )
    );

    foreach( $nameLocations as $langKey => $name ) {
      $rTypeData[ 'name_'.$langKey ] = $name;
    }

    $rTypeModel = new ResourcetypeModel( $rTypeData );
    if( !$rTypeModel->save() ) {
      $rTypeModel = false;
    }

    return $rTypeModel;
  }


  public function addRTypeToTopics( $rTypeId, $rTypeIdName ) {
    include APP_BASE_PATH.'/conf/inc/geozzyTopics.php';

    if( is_array( $geozzyTopicsInfo ) && count( $geozzyTopicsInfo ) > 0 ) {
      foreach( $geozzyTopicsInfo as $topicIdName => $topicInfo ) {

        if( array_key_exists( $rTypeIdName, $topicInfo['resourceTypes'] ) ) {
          error_log( 'addRTypeToTopics: Adding '.$rTypeIdName.' to '.$topicIdName );

          $topicModel = new TopicModel();
          $topicList = $topicModel->listItems( array( 'filters' => array( 'idName' => $topicIdName ) ) );
          $topicObj = false;
          if( !$topicList || !($topicObj = $topicList->fetch()) ) {
            // error_log( 'addRTypeToTopics: Creando '.$topicIdName );

            $topic = array( 'idName' => $topicIdName );
            foreach( $topicInfo['name'] as $langKey => $name ) {
              $topic[ 'name_'.$langKey ] = $name;
            }
            $topicObj = new TopicModel( $topic );
            $topicObj->save();
          }

          // Link Topic to RType
          $rTypeTopicParams = array(
            'topic' => $topicObj->getter('id'),
            'resourceType' => $rTypeId,
            'weight' => $topicInfo['weight']
          );
          $resourcetypeTopicModel = new ResourcetypeTopicModel( $rTypeTopicParams );
          $resourcetypeTopicModel->save();
        }
      }
    }
  }


  public function createTaxonomies( $taxGroups ) {
    filedata::load('controller/FiledataController.php');

    $fileDataControl = new FiledataController();

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

            if( isset( $term['icon'] ) ) {

              $iconPath = ModuleController::getRealFilePath( 'classes/'.$term['icon'] , $this->moduleClass );

              $iconPathSplit = explode('/',$iconPath);

              if( $icon = $fileDataControl->saveFile( $iconPath, '/initialIcons', array_pop($iconPathSplit) ,false ) ) {
                $term['icon'] = $icon->getter('id');
              }
              else{
                unset( $term['icon'] );
              }

            }


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


  public function getRtModels( $rTypeClassName ) {
    $retModels = array();

    if( class_exists( $rTypeClassName ) && property_exists( $rTypeClassName, 'rext' ) ) {
      $rTypeObj = new $rTypeClassName();
      if( is_array( $rTypeObj->rext ) && count( $rTypeObj->rext ) > 0 ) {
        foreach( $rTypeObj->rext as $rext ) {
          $retModels = array_merge( $retModels, $this->getRextModels( $rext )  );
        }
      }
    }

    return $retModels;
  }


  public function getRextModels( $rExtClassName ) {
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
