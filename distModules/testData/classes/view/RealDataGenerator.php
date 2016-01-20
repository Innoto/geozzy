<?php
Cogumelo::load('coreView/View.php');
Cogumelo::load('coreModel/DBUtils.php');

common::autoIncludes();
geozzy::autoIncludes();

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

    // Tipo: restaurante
    $rTypeModel = new ResourcetypeModel();
    $rTypeObj = $rTypeModel->listItems( array( 'filters' => array( 'idName' => 'rtypeRestaurant' )))->fetch();
    $rTypeId = $rTypeObj->getter('id');

    // Temática: probas
    $topicModel = new TopicModel();
    $topicObj = $topicModel->listItems( array( 'filters' => array( 'idName' => 'probasTopic' )))->fetch();
    $topicArray[0]['id'] = $topicObj->getter('id');
    $topicArray[0]['idName'] = 'probasTopic';

    // Taxonomías:
    $taxgroupArray[1] = 'eatanddrinkType';
    $taxgroupArray[2] = 'eatanddrinkSpecialities';
    $taxgroupArray[3] = 'starred';

    // user: establecemos 12 para poder buscar por este campo e borrar os recursos xerados aqui
    $user = 12;

    // texto aleatorio para o contido
    include 'randomText.php';
    $contentLength = rand(3,5000);
    $contentIni = rand(0,500);
    $contentRandom = substr($randomText, $contentIni, $contentLength);

    // Miramos se existen as carpetas, e se non existen as creamos

    if (!is_dir(MOD_FORM_FILES_APP_PATH.'/testData/')){
      mkdir(MOD_FORM_FILES_APP_PATH.'/testData/');
    }
    exec('cp '.COGUMELO_DIST_LOCATION.'/distModules/testData/classes/view/templates/images/* '.MOD_FORM_FILES_APP_PATH.'/testData/');

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

         $filedataArray[$datos[0]] = array('name' => $datos[3], 'originalName' => $datos[3],
                                        'absLocation' => '/testData/'.$datos[3],
                                        'type' => 'image/jpeg', 'size' => '38080');

         $loc = DBUtils::encodeGeometry( array('type'=>'POINT', 'data'=> array($data[$datos[0]]['lat'] , $data[$datos[0]]['lon']) ) );
         $zoom = 10;


         $rand1 = rand(500000,900000);
         $timeCreation = date( "Y-m-d H:i:s", time()-$rand1 );
         $dataRes[$datos[0]] = array(
           'title_'.LANG_DEFAULT => $data[$datos[0]]['title'],
           'title_en' => $data[$datos[0]]['title'],
           'title_gl' => $data[$datos[0]]['title'],
           'rTypeId' => $rTypeId, 'published' => 1,
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
          'timeCreation' => $timeCreation
        );

          $j = $j+1;
       }
    }


    for ($i = 1; $i <= $_POST['resNum']; $i++){
      $k = rand(1,$j);
      $resource =  new ResourceModel($dataRes[$k]);

      // asignamos unha imaxe ao recurso
      $resource->setterDependence( 'image', new FiledataModel( $filedataArray[$i] ) );

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

      // Cargamos as taxonomías (incluídas destacados)
      $taxTermModel = new TaxonomytermModel();
      $a=1; $b=1;
      foreach($taxgroupArray as $t=>$tax){
        $taxTermList[$tax] = $taxTermModel->listItems( array( 'filters' => array( 'TaxonomygroupModel.idName' => $tax ),
          'affectsDependences' => array( 'TaxonomygroupModel' ), 'joinType' => 'RIGHT' ) );

          while( $taxTerm = $taxTermList[$tax]->fetch() ){
            if ($tax == 'eatanddrinkType'){
              $termTypeArray[$a] = $taxTerm;
              $a = $a+1;
            }
            else{
              $taxTermArray[$b] = $taxTerm;
              $b = $b+1;
            }
          }
      }
      $randType = rand(1, $a-1);
      $resource->setterDependence( 'id', new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $termTypeArray[$randType]->getter('id'), 'weight' => 1)) );

      // asignamos especialidades ao recurso
      $usedTaxterm = array();
      if ($taxTermArray){
        $taxtermTimes = rand(1,$b-1);
        for ($c=1; $c<=$taxtermTimes; $c++){
            $taxtermNum = rand(1,$b-1);
            if (!in_array($taxTermArray[$taxtermNum]->getter('id'),$usedTaxterm)){
              $usedTaxterm[$c] = $taxTermArray[$taxtermNum]->getter('id');
              $resource->setterDependence( 'id', new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $taxTermArray[$taxtermNum]->getter('id'), 'weight' => 1)) );
            }
        }
      }

     $resource->save(array('affectsDependences' => true));

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
