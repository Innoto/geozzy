<?php
Cogumelo::load('coreView/View.php');
Cogumelo::load('coreModel/DBUtils.php');

common::autoIncludes();
geozzy::autoIncludes();

geozzy::load('controller/ResourceController.php');

class RealDataGenerator extends View
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

    $langDefault = Cogumelo::getSetupValue( 'lang:default' );

    // Tipo: restaurante
    $rTypeModel = new ResourcetypeModel();
    $rTypeObj = $rTypeModel->listItems( array( 'filters' => array( 'idName' => 'rtypeAppRestaurant' )))->fetch();
    $rTypeId = $rTypeObj->getter('id');

    // Temática: probas
    $topicModel = new TopicModel();
    $topicObj = $topicModel->listItems( array( 'filters' => array( 'idName' => 'probasTopic' )))->fetch();
    $topicArray[0]['id'] = $topicObj->getter('id');
    $topicArray[0]['idName'] = 'probasTopic';

    // Taxonomías:
    $taxgroupArray[1] = 'eatanddrinkType';
    $taxgroupArray[2] = 'eatanddrinkSpecialities';
    //$taxgroupArray[3] = 'starred';

    // user: establecemos 12 para poder buscar por este campo e borrar os recursos xerados aqui
    $user = 12000;

    // texto aleatorio para o contido
    include 'randomText.php';
    $contentLength = rand(3,5000);
    $contentIni = rand(0,500);
    $contentRandom = substr($randomText, $contentIni, $contentLength);

    $fileControl = new FiledataController();

    Cogumelo::disableLogs();

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
          'absLocation' => COGUMELO_DIST_LOCATION.'/distModules/testData/classes/view/images/'.$datos[3],
          'type' => 'image/jpeg', 'size' => '38080',
          'destDir' => '/testData/'
        );

         $loc = DBUtils::encodeGeometry( array('type'=>'POINT', 'data'=> array($data[$datos[0]]['lat'] , $data[$datos[0]]['lon']) ) );
         $zoom = 10;


         $rand1 = rand(500000,900000);
         $timeCreation = date( "Y-m-d H:i:s", time()-$rand1 );
         $user = 99999;
         // Publicado
         if ($randPublished = rand(1,8)) {
           if ($randPublished == 3 || $randPublished == 5) {
             $published = 0;
           }
           else {
             $published = 1;
           }
         }

         // creación del recurso

          $dataRes[$datos[0]] = array(
            'title_'.$langDefault => $data[$datos[0]]['title'],
            'title_en' => $data[$datos[0]]['title'],
            'title_gl' => $data[$datos[0]]['title'],
            'rTypeId' => $rTypeId,
            'shortDescription_'.$langDefault => $data[$datos[0]]['title'],
            'shortDescription_en' => $data[$datos[0]]['title'],
            'shortDescription_gl' => $data[$datos[0]]['title'],
            'mediumDescription_'.$langDefault => $data[$datos[0]]['mediumDescription'],
            'mediumDescription_en' => $data[$datos[0]]['mediumDescription'],
            'mediumDescription_gl' => $data[$datos[0]]['mediumDescription'],
            'content_'.$langDefault => $contentRandom,
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


    for ($i = 1; $i <= $_POST['resNum']; $i++){
      $k = rand(1,$j-1);
      $resource =  new ResourceModel($dataRes[$k]);

      // asignamos unha imaxe ao recurso
      $file = $fileControl->createNewFile( $filedataArray[$k] );
      $resource->setterDependence( 'image', $file );

      // asignamos temáticas ao recurso
      $resourcetype =  new ResourcetypeModel();
      $resource->setterDependence( 'id', new ResourceTopicModel( array('resource' => $resource->getter('id'), 'topic' => $topicArray[0]['id'])) );
      $resourcetypelist = $resourcetype->listItems( array( 'filters' => array( 'intopic' => $topicArray[0]['id'] ) ) )->fetchAll();

      $cont = 0;
      foreach ($resourcetypelist as $typeId => $type){
        $tiposArray[$cont] = $typeId;
        $cont = $cont + 1;
      }
      $t = rand(0,sizeof($tiposArray)-1);
      $resource->setter('rTypeId', $tiposArray[$t]);

      $resource->save(array('affectsDependences' => true));

      $resourcecontrol->setUrl($resource->getter('id'), $langDefault);

      // Cargamos as taxonomías (incluídas destacados)
      $taxTermModel = new TaxonomytermModel();
      $taxTermArray = array();
      $a=1; $b=1;
      foreach($taxgroupArray as $t=>$tax){
        $taxTermList[$tax] = $taxTermModel->listItems( array( 'filters' => array( 'TaxonomygroupModel.idName' => $tax ),
          'affectsDependences' => array( 'TaxonomygroupModel' ), 'joinType' => 'RIGHT' ) );

          while( $taxTerm = $taxTermList[$tax]->fetch() ){

            if ($tax == 'eatanddrinkType'){
              $termTypeArray[$a] = $taxTerm->getter('id');
              $a = $a+1;
            }
            else{
              $taxTermArray[$b] = $taxTerm->getter('id');
              $b = $b+1;
            }
          }

          if ($tax == 'eatanddrinkType'){
            $taxtermNum = rand(1, sizeof($termTypeArray)-1);
            $taxterm = $termTypeArray[$taxtermNum];
            $resTaxterm = new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $taxterm, 'weight' => 1));
            $resTaxterm->save();
          }
          else{
            for ($c=1; $c<=sizeof($taxTermArray)/2; $c++){
                $taxtermNum = rand(1, sizeof($taxTermArray)-1);
                $usedTaxterm[$c] = $taxTermArray[$taxtermNum];
                $taxterm = $taxTermArray[$taxtermNum];
                $resTaxterm = new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $taxterm, 'weight' => 1));
                $resTaxterm->save();
            }
          }
        }

       $price = rand(8,140);
       $capacity = rand(15, 200);
       $dataExt = array('resource'=> $resource->getter('id'), 'averagePrice' => $price, 'capacity' => $capacity);

       $eatanddrink = new EatAndDrinkModel($dataExt);
       $eatanddrink->save(array('affectsDependences' => false));

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
