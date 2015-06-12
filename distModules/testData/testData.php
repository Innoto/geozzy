<?php

Cogumelo::load("coreController/Module.php");


class testData extends Module
{

  public $includesCommon = array();

  public function __construct() {
    $this->addUrlPatterns( '#^testData$#', 'view:TestDataViewMaster::commonTestDataInterface' );
    $this->addUrlPatterns( '#^testData/resources/(.*)$#', 'view:TestDataGenerator::generateResources' );
  }


}
