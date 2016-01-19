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
    $type = 11;

    $topicArray[0]['id'] = 12;
    $topicArray[0]['idName'] = 'Probas';

    // Taxonomías:
    $taxgroupArray[0] = 'eatanddrinkType';
    $taxgroupArray[1] = 'eatanddrinkSpecialities';
    $taxgroupArray[3] = 'starred';

    // Cargamos as taxonomías (incluídas destacados)
    $taxTermModel = new TaxonomytermModel();
    foreach($taxgroupArray as $t=>$tax){
      $taxTermList[$tax] = $taxTermModel->listItems( array( 'filters' => array( 'TaxonomygroupModel.idName' => $tax ),
        'affectsDependences' => array( 'TaxonomygroupModel' ), 'joinType' => 'RIGHT' ) );
        $i=1;
        while( $taxTerm = $taxTermList[$tax]->fetch() ){

          echo '<pre>';

          $taxTermArray[$i] = $taxTerm;
          $i = $i+1;
        }
    }

    // Miramos se existen as carpetas, e se non existen as creamos
    if (!is_dir(MOD_FORM_FILES_APP_PATH.'/testData/')){
      mkdir(MOD_FORM_FILES_APP_PATH.'/testData/');
    }

    // Cargamos unhas imaxes
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
         $dataRes[$datos[0]] = array('title_'.LANG_DEFAULT => $data[$datos[0]]['title'], 'rTypeId' => $type, 'published' => 1, 'mediumDescription_'.LANG_DEFAULT => $data[$datos[0]]['mediumDescription'], 'user' =>  12,
          'loc' => $loc, 'defaultZoom' => $zoom, 'timeCreation' => $timeCreation);

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

      // asignamos taxonomías ao recurso
      $usedTaxterm = array();
      if ($taxTermArray){

        $taxtermTimes = rand(1,sizeof($taxTermArray));
        for ($c=1; $c<=$taxtermTimes; $c++){
            $taxtermNum = rand(1,sizeof($taxTermArray));
            if (!in_array($taxTermArray[$taxtermNum],$usedTaxterm)){
              $usedTaxterm[$c] = $taxTermArray[$taxtermNum];
              $resource->setterDependence( 'id', new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $taxTermArray[$taxtermNum]->getter('id'), 'weight' => 1)) );
            }
        }
      }

      // Grabamos las dependencias
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
