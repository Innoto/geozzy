<?php
geozzy::load('model/ResourcetypeModel.php');
require_once( APP_BASE_PATH.'/conf/inc/geozzyTopics.php');


class RTUtilsController {

  var $moduleClass = false;

  public function __construct( $moduleClass ) {
    $this->moduleClass = $moduleClass;

    $this->langDefault = Cogumelo::getSetupValue( 'lang:default' );
    $langsConf = Cogumelo::getSetupValue( 'lang:available' );
    if( is_array( $langsConf ) ) {
      $this->langAvailable = array_keys( $langsConf );
    }
  }

  public function rTypeModuleDeploy() {
    $this->rTypeModuleRc();
  }

  public function rExtModuleDeploy() {
    $this->rExtModuleRc();
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

    $existRTypeModel = ( new ResourcetypeModel() )->listItems(['filters'=>['idName'=>$rTypeIdName]])->fetch();
    if( $existRTypeModel ) {
      $rTypeData['id'] = $existRTypeModel->getter('id');
    }

    $rTypeModel = new ResourcetypeModel( $rTypeData );

    if( !$rTypeModel->save() ) {
      $rTypeModel = false;
    }

    return $rTypeModel;
  }



  public function addRTypeToTopics( $rTypeId, $rTypeIdName ) {

    global $geozzyTopicsInfo;
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
            $topic['weight'] = $topicInfo['weight'];


            $existTopicModel = ( new TopicModel() )->listItems(['filters'=>['idName'=> $topic['idName'] ]])->fetch();
            if( $existTopicModel ) {
              $topic['id'] = $existTopicModel->getter('id');
            }

            $topicObj = new TopicModel( $topic );
            $topicObj->save();


          }

          // Link Topic to RType
          $rTypeTopicParams = array(
            'topic' => $topicObj->getter('id'),
            'resourceType' => $rTypeId,
            'weight' => $topicInfo['resourceTypes'][ $rTypeIdName ]['weight']
          );


          $existResourcetypeTopicModel = ( new ResourcetypeTopicModel() )->listItems(['filters'=>['topic'=> $rTypeTopicParams['topic'], 'resourceType'=> $rTypeTopicParams['resourceType'] ]])->fetch();
          if( $existResourcetypeTopicModel ) {
            $rTypeTopicParams['id'] = $existResourcetypeTopicModel->getter('id');
          }


          $resourcetypeTopicModel = new ResourcetypeTopicModel( $rTypeTopicParams );
          $resourcetypeTopicModel->save();

          //
          // ADD topic taxonomygroup & taxonomyterms
          if( isset($topicInfo['taxonomies']) ) {




            $tax = $topicInfo['taxonomies'];
            foreach( $tax['name'] as $langKey => $name ) {
              $tax['name_'.$langKey] = $name;
            }

            if( (new TaxonomygroupModel)->listCount( ['filters'=>['idName'=> $tax['idName'] ]] ) === 0 ) {
              unset($tax['name']);

              $existTaxonomygroupModel = ( new TaxonomygroupModel() )->listItems(['filters'=>['idName'=> $tax['idName'] ]])->fetch();
              if( $existTaxonomygroupModel ) {
                $tax['id'] = $existTaxonomygroupModel->getter('id');
              }


              $taxgroup = new TaxonomygroupModel( $tax );
              $taxgroup->save();


              if( isset($tax['initialTerms']) && count( $tax['initialTerms']) > 0 ) {
                foreach( $tax['initialTerms'] as $term ) {
                  $term['taxgroup'] = $taxgroup->getter('id');

                  foreach( $term['name'] as $langKey => $name ) {
                     $term['name_'.$langKey] = $name;
                  }
                  unset($term['name']);

                  $existTaxonomytermModel = ( new TaxonomytermModel() )->listItems(['filters'=>['idName'=> $term['idName'], 'taxgroup'=> $term['taxgroup']  ]])->fetch();
                  if( $existTaxonomytermModel ) {
                    if( !$taxgroup->getter('editable') ) {
                      $term['id'] = $existTaxonomytermModel->getter('id');
                      $taxterm = new TaxonomytermModel( $term );
                      $taxterm->save();
                    }
                  }
                  else {
                    $taxterm = new TaxonomytermModel( $term );
                    $taxterm->save();
                  }

                }
              }

              // second save of topic taxgroup



              $topicObj->setter( 'taxgroup',  $taxgroup->getter('id') );
              $topicObj->save();
            }

          }


        }
      }
    }
  }




  public function createTaxonomies( $taxGroups ) {
    filedata::load('controller/FiledataController.php');

    $fileDataControl = new FiledataController();

    // echo "createTaxonomies\n"; print_r( $taxGroups );
    if( $taxGroups && is_array( $taxGroups ) && count( $taxGroups ) > 0 ) {
      foreach( $taxGroups as $taxKey => $tax ) {
        if( !empty( $tax['external'] ) ) {
          // Las taxonomias externas se crean en otro modulo
          echo 'TaxGroup '.$taxKey.' no creado por ser de otro modulo ('.$tax['external'].')'."\n";
          continue;
        }

        foreach( $tax['name'] as $langKey => $name ) {
          $tax['name_'.$langKey] = $name;
        }
        unset($tax['name']);


        $existTaxonomygroupModel = ( new TaxonomygroupModel() )->listItems(['filters'=>['idName'=> $tax['idName'] ]])->fetch();
        if( $existTaxonomygroupModel ) {
          $tax['id'] = $existTaxonomygroupModel->getter('id');
        }


        $taxgroup = new TaxonomygroupModel( $tax );
        $taxgroup->save();


        $idByIdName = [];
        $weight = 10;

        if( isset($tax['initialTerms']) && count( $tax['initialTerms'] ) > 0 ) {
          foreach( $tax['initialTerms'] as $term ) {
            $term['taxgroup'] = $taxgroup->getter('id');

            if( !empty( $term['icon'] ) ) {
              $iconAbs = ModuleController::getRealFilePath( 'classes/'.$term['icon'] , $this->moduleClass );
              if( !empty( $iconAbs ) && file_exists( $iconAbs ) ) {            
                // Cogumelo::debug( __METHOD__.' - iconAbs: ' . $iconAbs );
                $iconAbsNameExt = pathinfo( $iconAbs, PATHINFO_BASENAME );

                if( $icon = $fileDataControl->saveFile( $iconAbs, '/initialIcons', $iconAbsNameExt, false ) ) {
                  $term['icon'] = $icon->getter('id');
                }
                else{
                  Cogumelo::error( __METHOD__.' - ERROR: NO se ha asignado '.$iconAbs.' para '.$tax['idName'].'.'.$term['idName']);
                  unset( $term['icon'] );
                }
              }
              else{
                Cogumelo::error( __METHOD__.' - ERROR: NO existe '.$this->moduleClass.'/classes/'.$term['icon'].' para '.$tax['idName'].'.'.$term['idName']);
                unset( $term['icon'] );
              }
            }

            foreach( $term['name'] as $langKey => $name ) {
              $term['name_'.$langKey] = $name;
            }
            unset($term['name']);

            if( isset( $term['mediumDescription'] ) ) {
              foreach( $term['mediumDescription'] as $langKey => $name ) {
                $term['mediumDescription_'.$langKey] = $name;
              }
              unset($term['mediumDescription']);
            }

            if( isset( $term['parentIdName'] ) && isset( $idByIdName[ $term['parentIdName'] ] ) ) {
              $term['parent'] = $idByIdName[ $term['parentIdName'] ];
              unset( $term['parentIdName'] );
            }

            $term['weight'] = $weight;
            $weight += 10;




            /*$taxterm = new TaxonomytermModel( $term );
            $taxterm->save();*/


            $existTaxonomytermModel = ( new TaxonomytermModel() )->listItems(['filters'=>['idName'=> $term['idName'], 'taxgroup'=> $term['taxgroup']  ]])->fetch();
            if( $existTaxonomytermModel ) {
              if( !$taxgroup->getter('editable') ) {
                $term['id'] = $existTaxonomytermModel->getter('id');
                $taxterm = new TaxonomytermModel( $term );
                $taxterm->save();
                $idByIdName[ $term['idName'] ] = $taxterm->getter('id');
              }
            }
            else {
              $taxterm = new TaxonomytermModel( $term );
              $taxterm->save();
              $idByIdName[ $term['idName'] ] = $taxterm->getter('id');
            }







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
