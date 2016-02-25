<?php
Cogumelo::load('coreView/View.php');
Cogumelo::load('coreModel/DBUtils.php');

common::autoIncludes();
geozzy::autoIncludes();

geozzy::load('controller/ResourceController.php');

class InitResourcesView extends View
{

  public function __construct( $base_dir ) {
    parent::__construct($base_dir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    return true;
  }

  public function generateResources(){

    global $LANG_AVAILABLE;
    $resourcecontrol = new ResourceController();

    include(APP_BASE_PATH.'/conf/initResources/resources.php');

    $resourceType = new ResourcetypeModel();
    $fileControl = new FiledataController();
    $taxonomyTerm = new TaxonomytermModel();
    $urlAlias = new urlAliasModel();

    foreach($initResources as $initRes){

      // Tipo e recurso base
      $rtypeIdName = $initRes['rType'];
      $rType = $resourceType->listItems(array('filters' => array('idName'=>$rtypeIdName)))->fetch();

      $timeCreation = date( "Y-m-d H:i:s", time()-rand(500000,900000) );

      $resData = array('rTypeId'=>$rType->getter('id'), 'published' => 1, 'timeCreation' => $timeCreation);

      // Campos multiidioma: título e descripción
      foreach($LANG_AVAILABLE as $key=>$lang){
        $resData['title_'.$key] = $initRes['title'][$key];
        if ($initRes['shortDescription']){
          $resData['shortDescription_'.$key] = $initRes['shortDescription'][$key];
        }
      }

      // creamos o recurso
      $resource = new ResourceModel($resData);
      $resource->save();

      // Unha vez creado o recurso, creamos as súas relacións

      // image
      if ($initRes['img']){
        $filedata = array(
            'name' => $initRes['img'],
            'absLocation' => APP_BASE_PATH.'/conf/initResources/files/'.$initRes['img'],
            'type' => 'image/jpeg', 'size' => '38080'
        );
        $file = $fileControl->createNewFile( $filedata );
        $resource->setterDependence( 'image', $file );
        $resource->save(array('affectsDependences' => true));
      }

      // taxanomies
      $taxterm = $taxonomyTerm->listItems(array('filters'=>array('idName' => $initRes['viewType'])))->fetch();
      $resTaxterm = new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $taxterm->getter('id'), 'weight' => 1));
      $resTaxterm->save();

      //urlAlias multiidioma
      foreach($LANG_AVAILABLE as $key=>$lang){
          $resourcecontrol->setUrl($resource->getter('id'), $key, $initRes['urlAlias'][$key]);
      }
    }

    echo 'Páxinas base creadas';

  }

}
