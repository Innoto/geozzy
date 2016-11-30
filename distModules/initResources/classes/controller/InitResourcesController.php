<?php

geozzy::load('controller/ResourceController.php');
geozzy::load('model/ResourceModel.php');



class InitResourcesController{

  public function __construct() {
  }


  public function generateResources( $isFirstGenerateModel = false ) {
    include( APP_BASE_PATH.'/conf/initResources/resources.php' );

    foreach( $initResources as $initRes ) {
      if( preg_match( '#^(.*)\#(\d{1,10}(.\d{1,10})?)#', $initRes['version'], $matches ) ) {
        $deployModuleName = $matches[1];

        eval( '$currentModuleVersion = (float) '.$deployModuleName.'::checkCurrentVersion();' );
        eval( '$registeredModuleVersion = (float) '.$deployModuleName.'::checkRegisteredVersion();' );

        $deployModuleVersion = (float) $matches[2];

        if( class_exists( $deployModuleName ) ) {
          if( $isFirstGenerateModel === true
            && isset( $initRes['executeOnGenerateModelToo'])
            && $initRes['executeOnGenerateModelToo'] === true )
          {
            $this->generateResource( $initRes );
          }
          elseif( $deployModuleVersion > $registeredModuleVersion
            &&  $deployModuleVersion <= $currentModuleVersion )
          {
            $this->generateResource( $initRes );
          }
        }
      }
    }

    echo 'Base resources created';
  }




  public function generateResource( $initRes ) {
    $resourcecontrol = new ResourceController();
    $resourceType = new ResourcetypeModel();
    $fileControl = new FiledataController();
    $taxViewModel = new TaxonomyViewModel();
    $urlAlias = new urlAliasModel();

    // Tipo e recurso base
    $rtypeIdName = $initRes['rType'];
    $rType = $resourceType->listItems( array('filters' => array('idName'=>$rtypeIdName)) )->fetch();

    $timeCreation = date( "Y-m-d H:i:s", time() );

    $resData = array(
      'idName' => isset( $initRes['idName'] ) ? $initRes['idName'] : null,
      'rTypeId' => $rType->getter('id'),
      'published' => 1,
      'timeCreation' => $timeCreation
    );

    // Campos multiidioma: título, descripción corta y media, y contenido
    foreach( Cogumelo::getSetupValue('lang:available') as $langKey => $lang ) {
      if( isset( $initRes['title'][$langKey] ) ) {
        $resData['title_'.$langKey] = $initRes['title'][$langKey];
      }
      if( isset( $initRes['shortDescription'][$langKey] ) ) {
        $resData['shortDescription_'.$langKey] = $initRes['shortDescription'][$langKey];
      }
      if( isset( $initRes['mediumDescription'][$langKey] ) ) {
        $resData['mediumDescription_'.$langKey] = $initRes['mediumDescription'][$langKey];
      }
      if( isset( $initRes['content'][$langKey] ) ) {
        $resData['content_'.$langKey] = $initRes['content'][$langKey];
      }
    }

    // creamos o recurso
    $resource = new ResourceModel( $resData );
    $resource->save();

    // Unha vez creado o recurso, creamos as súas relacións

    // image
    if( isset( $initRes['img'] ) && $initRes['img'] ) {
      $filedata = array(
        'name' => $initRes['img'],
        'destDir' => ResourceModel::$cols['image']['uploadDir'],
        'absLocation' => APP_BASE_PATH.'/conf/initResources/files/'.$initRes['img']
      );
      $file = $fileControl->createNewFile( $filedata );
      $resource->setterDependence( 'image', $file );
      $resource->save(array('affectsDependences' => true));
    }


    // taxanomies
    $confTerms = isset( $initRes['terms'] ) ? $initRes['terms'] : [];

    if( isset( $initRes['viewType'] ) ) {
      $confTerms['viewAlternativeMode'] = $initRes['viewType'];
    }

    if( count( $confTerms ) > 0 ) {
      $resId = $resource->getter('id');
      foreach( $confTerms as $taxGroupIdName => $idName ) {
        // echo "\n".'  Buscando un termino con idName '.$idName.' en taxGroupIdName '.$taxGroupIdName."\n";
        $termList = $taxViewModel->listItems( array( 'filters'=>array(
          'idName' => $idName,
          'taxGroupIdName' => $taxGroupIdName
        ) ) );
        $termObj = ( $termList ) ? $termList->fetch() : false;
        if( $termObj ) {
          $termId = $termObj->getter('id');
          // echo '  Engado o termino '.$termId.' a '.$resId."\n";
          $resTaxterm = new ResourceTaxonomytermModel( array('resource' => $resId, 'taxonomyterm' => $termId) );
          $resTaxterm->save();
        }
        else {
          echo "\n".'  ERROR: No existe un termino con idName '.$idName.' en taxGroupIdName '.$taxGroupIdName."\n";
        }
      }
    }


    //urlAlias multiidioma
    foreach( Cogumelo::getSetupValue('lang:available') as $langKey => $lang ) {
      if( isset( $initRes['urlAlias'][$langKey] ) ) {
        $resourcecontrol->setUrl( $resource->getter('id'), $langKey, $initRes['urlAlias'][$langKey] );
      }
    }
  }

} // class InitResourcesController
