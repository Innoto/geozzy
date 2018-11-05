<?php

geozzy::load('controller/ResourceController.php');
geozzy::load('model/ResourceModel.php');



class InitResourcesController{

  public function __construct() {
  }


  public function generateResources( $isFirstGenerateModel = false ) {
    include( APP_BASE_PATH.'/conf/initResources/resources.php' );

    foreach( $initResources as $initRes ) {
      $this->generateResource( $initRes );
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


    if( $rType === null) {
      $rtypeID = null;
    }
    else {
      $rtypeID = $rType->getter('id');
    }

    $resData = array(
      'idName' => isset( $initRes['idName'] ) ? $initRes['idName'] : null,
      'rTypeId' => $rtypeID,
      'published' => 1,
      'timeCreation' => isset( $initRes['timeCreation'] ) ? $initRes['timeCreation'] : $timeCreation,
      'loc' => isset( $initRes['loc'] ) ? $initRes['loc'] : null,
      'defaultZoom' => isset( $initRes['defaultZoom'] ) ? $initRes['defaultZoom'] : null,
      'weight' => isset( $initRes['weight'] ) ? $initRes['weight'] : null,
      'externalUrl' => isset( $initRes['externalUrl'] ) ? $initRes['externalUrl'] : null
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
    $existResourceModel = ( new ResourceModel() )->listItems(['filters'=>['idName'=> $resData['idName'] ]])->fetch();
    if( $existResourceModel ) {

      if( !$existResourceModel->getter('timeLastUpdate') ) {
        $resData['id'] = $existResourceModel->getter('id');
        $resource = new ResourceModel( $resData );
        $resource->save();
        $resEdited=true;
      }
      else {
        $resEdited=false;
      }
    }
    else {
      $resource = new ResourceModel( $resData );
      $resource->save();
      $resEdited=true;
    }




    if( $resEdited == true ) { // is edited

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




            $rtaxtermData = [
//              'idName' => $idName,
              'resource' => $resId,
              'taxonomyterm' => $termId
            ];


            $existTaxTermModel = ( new ResourceTaxonomytermModel() )->listItems(['filters'=>$rtaxtermData])->fetch();
            if( $existTaxTermModel ) {
              //$topic['id'] = $existTaxTermModel->getter('id');
            }
            else {
              $resTaxterm = new ResourceTaxonomytermModel( $rtaxtermData );
              $resTaxterm->save();
            }


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

      //rExt
      if (isset( $initRes['rExt'] ) && $rExtList = $initRes['rExt']){
        foreach ($rExtList as $rExtName => $rExtData){
          $rExtData['resource'] = $resource->getter('id');
          foreach ($rExtData as $fieldName => $fieldValue){
            if(is_array($fieldValue)){//campos multiiidioma
              foreach( Cogumelo::getSetupValue('lang:available') as $langKey => $lang ) {
                if(!empty($fieldValue[$langKey])){
                  $resData[$fieldName.'_'.$langKey] = $fieldValue[$langKey];
                }
              }
            }
            else{
              $resData[$fieldName] = $fieldValue;
            }
          }
          $rExtModel = $rExtName.'Model';
          $existRextModel = ( new $rExtModel() )->listItems(['filters'=>['resource'=> $resource->getter('id') ]])->fetch();
          if( $existRextModel ) {
            $resData['id'] = $existRextModel->getter('id');
          }
          $rExt = new $rExtModel($resData);
          $rExt->save();
        }
      }

    }
  }
} // class InitResourcesController
