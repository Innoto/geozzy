<?php

geozzy::load('controller/ResourceController.php');
geozzy::load('model/ResourceModel.php');

class InitResourcesController{

  public function __construct(){

  }

  public function generateResources(){

    global $LANG_AVAILABLE;
    $resourcecontrol = new ResourceController();

    include(APP_BASE_PATH.'/conf/initResources/resources.php');

    $resourceType = new ResourcetypeModel();
    $fileControl = new FiledataController();
    $taxonomyTerm = new TaxonomytermModel();
    $urlAlias = new urlAliasModel();

    foreach( $initResources as $initRes ) {

      // Tipo e recurso base
      $rtypeIdName = $initRes['rType'];
      $rType = $resourceType->listItems(array('filters' => array('idName'=>$rtypeIdName)))->fetch();

      $timeCreation = date( "Y-m-d H:i:s", time() );

      $resData = array( 'rTypeId'=>$rType->getter('id'), 'published' => 1, 'timeCreation' => $timeCreation );

      // Campos multiidioma: título e descripción
      foreach( Cogumelo::getSetupValue('lang:available') as $key => $lang ) {
        $resData['title_'.$key] = $initRes['title'][$key];
        if( $initRes['shortDescription'] ) {
          $resData['shortDescription_'.$key] = $initRes['shortDescription'][$key];
        }
      }

      // creamos o recurso
      $resource = new ResourceModel($resData);
      $resource->save();

      // Unha vez creado o recurso, creamos as súas relacións

      // image
      if( $initRes['img'] ) {
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
      if( isset($initRes['viewType']) ) {
        $taxterm = $taxonomyTerm->listItems(array('filters'=>array('idName' => $initRes['viewType'])))->fetch();
        if( $taxterm ) {
          $resTaxterm = new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'),
            'taxonomyterm' => $taxterm->getter('id'), 'weight' => 1));
          $resTaxterm->save();
        }
        else {
          echo "\n".'  ERROR: No existe un taxTerm con idName '.$initRes['viewType']."\n";
        }
      }

      //urlAlias multiidioma
      foreach( Cogumelo::getSetupValue('lang:available') as $key => $lang ) {
        $resourcecontrol->setUrl($resource->getter('id'), $key, $initRes['urlAlias'][$key]);
      }
    }

    echo 'Base resources created';

  }

} // class InitResourcesController
