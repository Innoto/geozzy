<?php

Cogumelo::load("coreController/Module.php");


class testData extends Module
{

  public $includesCommon = array();

  public function __construct() {
    $this->addUrlPatterns( '#^testData$#', 'view:TestDataViewMaster::commonTestDataInterface' );
    $this->addUrlPatterns( '#^testData/generate$#', 'view:TestDataGenerator::generateResources' );
    $this->addUrlPatterns( '#^realData/generate$#', 'view:RealDataGenerator::generateResources' );
  }


}
