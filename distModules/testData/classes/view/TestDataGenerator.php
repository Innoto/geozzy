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
    for ($i = 1; $i <= $request[1]; $i++){

      // título
      $titleLength = rand(1,250);
      $titleIni = rand(0,200);
      $titleRandom = substr(("Morbi metus nulla, hendrerit ut aliquam in, laoreet feugiat dui. Aenean ac massa nulla. Aenean venenatis ex quis ipsum tempus dapibus. Proin efficitur, sapien eget convallis ultrices, quam mauris semper enim, sit amet volutpat purus 
        diam sit amet nisi. Vestibulum vestibulum a ligula ut facilisis"), $titleIni, $titleLength);

      // descripcion
      $descLength = rand(1,150);
      $descIni = rand(0,200);
      $descRandom = substr(("Aliquam posuere erat eu feugiat vestibulum. Ut magna eros, vehicula ut elementum vel, mattis id leo. Pellentesque ac ex nibh. Nulla interdum aliquam pretium. Sed porttitor arcu quis mi ullamcorper, tempus faucibus diam pharetra. 
        Mauris sagittis mi eget faucibus auctor. Nullam aliquet erat nec enim condimentum viverra. Quisque finibus cursus auctor. Praesent orci nibh, volutpat eget orci eget, porta condimentum orci. Fusce nec dictum sapien, vel fringilla purus. Nullam eget lobortis lorem. Pellentesque imperdiet 
        ante mauris, sit amet faucibus sem consequat ut."), $descIni, $descLength);

      // contentido
      $contentLength = rand(3,5000);
      $contentIni = rand(0,500);
      $contentRandom = substr(" Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur lectus nunc, luctus at nisi non, congue efficitur velit. 
        Duis semper pellentesque neque, sed faucibus metus luctus ac. Aliquam posuere erat eu feugiat vestibulum. Ut magna eros, vehicula ut elementum vel, 
        mattis id leo. Pellentesque ac ex nibh. Nulla interdum aliquam pretium. Sed porttitor arcu quis mi ullamcorper, tempus faucibus diam pharetra. 
        Mauris sagittis mi eget faucibus auctor. Nullam aliquet erat nec enim condimentum viverra. Quisque finibus cursus auctor. Praesent orci nibh, 
        volutpat eget orci eget, porta condimentum orci. Fusce nec dictum sapien, vel fringilla purus. Nullam eget lobortis lorem. Pellentesque imperdiet 
        ante mauris, sit amet faucibus sem consequat ut. Morbi metus nulla, hendrerit ut aliquam in, laoreet feugiat dui. Aenean ac massa nulla. 
        Aenean venenatis ex quis ipsum tempus dapibus. Proin efficitur, sapien eget convallis ultrices, quam mauris semper enim, sit amet volutpat purus 
        diam sit amet nisi. Vestibulum vestibulum a ligula ut facilisis. Nam molestie eros quis libero dapibus, a tempus lorem pulvinar. 
        Morbi eget nisl ut enim rutrum efficitur. In hac habitasse platea dictumst. ", $contentIni, $contentLength);

      // imagenes
      $img[1] = 'templates/img/foto1.jpg';
      $img[2] = 'templates/img/foto2.jpg';
      $img[3] = 'templates/img/foto3.jpg';
      $foto = $img[rand(1,3)];

      // Publicado
      if ($randPublished = rand(1,8)){
        if ($randPublished == 3 || $randPublished == 5)
          $published = 0;
        else
          $published = 1;
      }

      // Tipos de recurso
      $resourcetypeModel = new ResourcetypeModel();
      $typeList = $resourcetypeModel->listItems()->fetchAll();

      $k = 0;
      foreach ($typeList as $type){
        $typeArray[$k] = $type->getter('id');
        $k = $k+1;
      }
      $typeNum = rand(1,$k-1);

      // creación del recurso
      $data = array('title_'.LANG_DEFAULT => $titleRandom, 'type' => $typeArray[$typeNum], 'published' => $published, 'shortDescription_'.LANG_DEFAULT => $descRandom, 'content_'.LANG_DEFAULT => $contentRandom);
      $resource =  new ResourceModel($data);
      $resource->save();

/*      $taxgroup = new TaxonomygroupModel();
      $taxgroupList = $taxgroup->listItems()->fetchAll();

      $taxterm = new ResourceTaxonomytermModel();

      foreach($taxgroupList as $taxgroup){
        $taxtermArray[$taxgroup->getter('id')] = $taxterm->listItems(array('filters' => array('taxgroup' => $taxgroup->getter('id'))));
      }

      print_r($taxterm);
      */

      // Asignamos temáticas
      $topicModel = new TopicModel();
      $topicList = $topicModel->listItems()->fetchAll();
       $l = 1;
      foreach ($topicList as $topic){
        $topicArray[$l] = $topic->getter('id');
        $l = $l+1;
      }
      $topicTimes = rand(1,$l-1);

      for ($a=1; $a<=$topicTimes; $a++){
          $topicNum = rand(1,$l-1);
          $resource->setterDependence( 'id', new ResourceTopicModel( array('resource' => $resource->getter('id'), 'topic' => $topicArray[$topicNum])) );
      }

      $taxtermModel = new TaxonomytermModel();
      // Asignamos destacados
      $starredList = $taxtermModel->listItems(array( 'filters' => array( 'TaxonomygroupModel.idName' => 'starred' ), 'affectsDependences' => array('TaxonomygroupModel'), 'joinType' => 'RIGHT' ))->fetchAll();;
      
      $j = 1;
      foreach ($starredList as $star){
        $starArray[$j] = $star->getter('id');
        $j = $j+1;
      }
      $starTimes = rand(1,$j-1);
      for ($b=1; $b<=$starTimes; $b++){
        $taxtermNum = rand(1,$j-1);
        $resource->setterDependence( 'id', new ResourceTaxonomytermModel( array('resource' => $resource->getter('id'), 'taxonomyterm' => $starArray[$taxtermNum])) );
      }

      // Grabamos las dependencias
      $resource->save(array('affectsDependences' => true));
    }
  }

  public function commonTestDataInterface(){
    $this->template->setTpl('testDataMaster.tpl', 'testData');
    $this->template->exec();
  }

  public function getPanelBlock( $content, $title = '', $icon = 'fa-info' ) {
    $template = new Template( $this->baseDir );

    if( is_string( $content ) ) {
      $template->assign( 'content', $content );
    }
    else {
      $template->setBlock( 'content', $content );
    }
    $template->assign( 'title', $title );
    $template->assign( 'icon', $icon );
    $template->setTpl( 'testDataPanel.tpl', 'testData' );

    return $template;
  }

}
