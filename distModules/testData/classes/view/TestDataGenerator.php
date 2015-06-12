<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
geozzy::autoIncludes();

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

  public function generateResources($request){

    // Cargamos os tipos de recurso
    $resourcetypeModel = new ResourcetypeModel();
    $typeList = $resourcetypeModel->listItems()->fetchAll();
    $k = 0;
    foreach ($typeList as $type){
      $typeArray[$k] = $type->getter('id');
      $k = $k+1;
    }

    // Cargamos as taxonomías (incluídas destacados)
    $taxtermModel = new TaxonomytermModel();
    $taxtermList = $taxtermModel->listItems()->fetchAll();
    $m = 1;
    foreach ($taxtermList as $taxterm){
      $taxtermArray[$m] = $taxterm->getter('id');
      $m = $m+1;
    }

    // Cargamos as temáticas
    $topicModel = new TopicModel();
    $topicList = $topicModel->listItems()->fetchAll();
    $l = 1;
    foreach ($topicList as $topic){
      $topicArray[$l] = $topic->getter('id');
      $l = $l+1;
    }


    for ($i = 1; $i <= $request[1]; $i++){

      include 'randomText.php';

      // título en idioma por defecto
      $titleLength = rand(1,250);
      $titleIni = rand(0,200);
      $titleRandom = substr($randomText, $titleIni, $titleLength);

      // título traducido 
      $titleEnLength = rand(1,250);
      $titleEnIni = rand(0,200);
      // traducido?
      if ($randTranslation = rand(1,6)){
        if ($randTranslation == 2)
          $titleEnRandom = substr($randomText, $titleEnIni, $titleEnLength);
        else
          $titleEnRandom = NULL;
      }      

      // descripcion en idioma por defecto
      $descLength = rand(1,150);
      $descIni = rand(0,200);
      $descRandom = substr($randomText, $descIni, $descLength);

      // contentido en idioma por defecto
      $contentLength = rand(3,5000);
      $contentIni = rand(0,500);
      $contentRandom = substr($randomText, $contentIni, $contentLength);

      // Publicado
      if ($randPublished = rand(1,8)){
        if ($randPublished == 3 || $randPublished == 5)
          $published = 0;
        else
          $published = 1;
      }

      // generamos a clave para o array de tipos
      $typeNum = rand(1,$k-1);

      // creación del recurso
      $data = array('title_'.LANG_DEFAULT => $titleRandom, 'title_en' => $titleEnRandom,'type' => $typeArray[$typeNum], 'published' => $published, 'shortDescription_'.LANG_DEFAULT => $descRandom, 'content_'.LANG_DEFAULT => $contentRandom);
      $resource =  new ResourceModel($data);
      $resource->save();

      // asignamos taxonomías ao recurso
      $taxtermTimes = rand(1,$m-1);
      for ($c=1; $c<=$taxtermTimes; $c++){
          $taxtermNum = rand(1,$m-1);
          $resource->setterDependence( 'id', new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $taxtermArray[$taxtermNum])) );
      }

      // asignamos temáticas ao recurso
      $topicTimes = rand(1,$l-1);
      for ($a=1; $a<=$topicTimes; $a++){
          $topicNum = rand(1,$l-1);
          $resource->setterDependence( 'id', new ResourceTopicModel( array('resource' => $resource->getter('id'), 'topic' => $topicArray[$topicNum])) );
      }

      // Grabamos las dependencias
      $res = $resource->save(array('affectsDependences' => true));
    }
    echo "Recursos creados!";
  }

  public function commonTestDataInterface(){
    $this->template->setTpl('testDataMaster.tpl', 'testData');
    $this->template->exec();
  }

}
