<?php
Cogumelo::load('coreView/View.php');
Cogumelo::load('coreModel/DBUtils.php');

common::autoIncludes();
geozzy::autoIncludes();

geozzy::load('controller/ResourceController.php');

class TestDataGenerator extends View
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

    $resourcecontrol = new ResourceController();

    // Listado de taxonomías únicas (personalizable para cada proxecto)
    $onlyOneTax = array('rextAppZonaType', 'accommodationCategory');

    // Cargamos os tipos de recurso, excluíndo os de sistema, e as súas taxonomías
    $resourcetypeModel = new ResourcetypeModel();
    $typeList = $resourcetypeModel->listItems()->fetchAll();
    $k = 0;
    foreach ($typeList as $type){
      $rtypeName = $type->getter('idName');
      if ($rtypeName !== 'rtypeFile' && $rtypeName !== 'rtypeUrl' && $rtypeName !== 'rtypePage'){
        $tipos[$k]['id'] = $type->getter('id');
        $tipos[$k]['idName'] = $rtypeName;
        $rtype = new $rtypeName();
        $typeArray[$type->getter('id')]['rtype'] = $rtypeName;

        foreach($rtype->rext as $rext){
          $rextName = $rext;
          $rextModel = new $rextName();
          $rextTax = $rextModel->taxonomies;

          $rextModelName = false;
          if (sizeof($rextModel->models)>0){
            $rextModelName = $rextModel->models;
          }

          $taxgroupArray = array();
          foreach($rextModel->taxonomies as $key=>$taxgroup){
            array_push ($taxgroupArray , $taxgroup['idName']);
            $taxterm = array();
            foreach($resourcecontrol->getOptionsTax($taxgroup['idName']) as $key=>$value){
              array_push($taxterm, $key);
            }
            $taxtermArray[$taxgroup['idName']]=$taxterm;
          }
          $typeArray[$type->getter('id')]['taxonomies'][$rextName]['terms'] = $taxgroupArray;
          $typeArray[$type->getter('id')]['taxonomies'][$rextName]['model'] = $rextModelName;
        }
        $k = $k+1;
      }
    }


    // Cargamos as temáticas
    $topicModel = new TopicModel();
    $topicList = $topicModel->listItems()->fetchAll();
    $l = 1;
    if ($topicList){
      foreach ($topicList as $topic){
        $topicArray[$l] = $topic->getter('id');
        $l = $l+1;
      }
    }

    // texto aleatorio para o contido
    include 'randomText.php';
    $contentLength = rand(3,5000);
    $contentIni = rand(0,500);
    $contentRandom = substr($randomText, $contentIni, $contentLength);

    $fileControl = new FiledataController();

    // Cargamos el array de datos
    $data = array();
    $j = 1;
    if (($fichero = fopen(COGUMELO_DIST_LOCATION.'/distModules/testData/classes/view/datos.csv', "r")) !== FALSE) {
       while (($datos = fgetcsv($fichero, 0, ";")) !== FALSE) {
         // Procesar los datos.
         $data[$datos[0]]['lat'] = $datos[1];
         $data[$datos[0]]['lon'] = $datos[2];
         $data[$datos[0]]['img'] = '/testData/'.$datos[3];
         $data[$datos[0]]['title'] = $datos[4];
         $data[$datos[0]]['mediumDescription'] = $datos[5];

         $filedataArray[$datos[0]] = array(
                                        'name' => $datos[3],
                                        'originalName' => $datos[3],
                                        'absLocation' => COGUMELO_DIST_LOCATION.'/distModules/testData/classes/view/templates/images/'.$datos[3],
                                        'type' => 'image/jpeg', 'size' => '38080',
                                        'destDir' => '/testData/'
                                      );

         $loc = DBUtils::encodeGeometry( array('type'=>'POINT', 'data'=> array($data[$datos[0]]['lat'] , $data[$datos[0]]['lon']) ) );
         $zoom = 10;

         $urlArray[$datos[0]] = '/'.str_replace(' ', '-', $data[$datos[0]]['title']);

         // generamos a clave para o array de tipos
         $typeNum = rand(0,$k-1);
         $rTypeId = $tipos[$typeNum]['id'];

         $rand1 = rand(500000,900000);
         $rand2 = rand(0,500000);
         $timeCreation = date( "Y-m-d H:i:s", time()-$rand1 );
         $timeLastUpdate = date( "Y-m-d H:i:s", time()-$rand2 );

         // user: establecemos 12 para poder buscar por este campo e borrar os recursos xerados aqui
         $user = 99999;
         // Publicado
         if ($randPublished = rand(1,8)){
           if ($randPublished == 3 || $randPublished == 5)
             $published = 0;
           else
             $published = 1;
         }

         // creación del recurso
          $dataRes[$j] = array(
            'title_'.LANG_DEFAULT => $data[$datos[0]]['title'],
            'title_en' => $data[$datos[0]]['title'],
            'title_gl' => $data[$datos[0]]['title'],
            'rTypeId' =>  $rTypeId,
            'shortDescription_'.LANG_DEFAULT => $data[$datos[0]]['title'],
            'shortDescription_en' => $data[$datos[0]]['title'],
            'shortDescription_gl' => $data[$datos[0]]['title'],
            'mediumDescription_'.LANG_DEFAULT => $data[$datos[0]]['mediumDescription'],
            'mediumDescription_en' => $data[$datos[0]]['mediumDescription'],
            'mediumDescription_gl' => $data[$datos[0]]['mediumDescription'],
            'content_'.LANG_DEFAULT => $contentRandom,
            'content_en' => $contentRandom,
            'content_gl' => $contentRandom,
            'user' =>  $user,
            'loc' => $loc,
            'defaultZoom' => $zoom,
            'timeCreation' => $timeCreation,
            'published' => $published
         );

        $j = $j+1;
       }
    }

    Cogumelo::disableLogs();

    for ($i = 1; $i <= $_POST['resNum']; $i++){

      $res = rand(1,$j-1);
      $resource =  new ResourceModel($dataRes[$res]);

      $actType = $resource->getter('rTypeId');

      // asignamos unha imaxe ao recurso
      $file = $fileControl->createNewFile( $filedataArray[$res] );
      $resource->setterDependence( 'image', $file );

      $resource->save(array('affectsDependences' => true));

      $taxtermlist = array();
      foreach($typeArray[$actType]['taxonomies'] as $key => $taxonomygroup){

        if ($taxonomygroup['terms']){
          $usedTaxterm = array();
          foreach ($taxonomygroup['terms'] as $taxonomygroupname){
            $taxtermlist = $taxtermArray[$taxonomygroupname];
            if (in_array($taxonomygroupname, $onlyOneTax)){ //taxonomias simples
              $taxtermNum = rand(0, sizeof($taxtermlist)-1);
              $taxterm = $taxtermlist[$taxtermNum];
              $resTaxterm = new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $taxterm, 'weight' => 1));
              $resTaxterm->save();
            }
            else{ // taxonomias multiples
              for ($c=1; $c<=sizeof($taxtermArray)/2; $c++){
                  $taxtermNum = rand(0, sizeof($taxtermlist)-1);
                  if (!in_array($taxtermlist[$taxtermNum],$usedTaxterm)){
                    $usedTaxterm[$c] = $taxtermlist[$taxtermNum];
                    $taxterm = $taxtermlist[$taxtermNum];
                    $resTaxterm = new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $taxterm, 'weight' => 1));
                    $resTaxterm->save();
                  }
              } // for
            } // if-else
          } // foreach ($taxonomygroup as $taxonomygroupname)
        } // if ($taxonomygroup)


        $rextModelName = $taxonomygroup['model'][0];
        if (isset($rextModelName) && $rextModelName!=''){
          $dataExt = array('resource'=> $resource->getter('id'));
          $rextModel = new $rextModelName($dataExt);
          $rextModel->save(array('affectsDependences' => false));
        }
      } // foreach($typeArray[$actType]['taxonomies'] as $taxonomygroup)


       // asignamos temáticas ao recurso
      $typetopic = new ResourcetypeTopicModel();
      $typetopicList = $typetopic->listItems(array('filters'=>array('resourceType' => $resource->getter('rTypeId'))))->fetchAll();

      if ($typetopicList){
        foreach($typetopicList as $typetopic){
          $resource->setterDependence( 'id', new ResourceTopicModel( array('resource' => $resource->getter('id'), 'topic' => $typetopic->getter('topic'))) );
        }
      }

      // Grabamos las dependencias
      $res = $resource->save(array('affectsDependences' => true));

      $resourcecontrol->setUrl($resource->getter('id'), LANG_DEFAULT);
    }

    echo "Recursos creados!";
  }

  public function commonTestDataInterface(){
    $this->template->setTpl('testDataMaster.tpl', 'testData');
    $this->template->exec();
  }


  function randomCoord($min, $max)
  {
    $range = $max-$min;
    $num = $min + $range * mt_rand(0, 32767)/32767;

    $num = round($num, 4);

    return str_replace( ',', '.' ,((string) $num) );
  }

}
