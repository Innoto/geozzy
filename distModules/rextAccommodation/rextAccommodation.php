<?php

Cogumelo::load("coreController/Module.php");

class rextAccommodation extends Module
{

  public $name = "rextAccomodation";
  public $version = "1.0";
  public $dependences = array();
  public $includesCommon = array();

  public $models = array(
  	'AccommodationModel'
  );
  
  public $taxonomies = array(
  
    'accommodationType' => array(
      'idName' => 'accommodationType',
      'name_en' => 'Type',
      'editable' => 1,
      'initialTerms' => array(
        array( 'idName' => 'hotel', 'name_en' => 'Hotel' ),
        array( 'idName' => 'hostel', 'name_en' => 'Hostel' ),
        array( 'idName' => 'camping', 'name_en' => 'Camping' )
      )
    ),

  	'accommodationCategory' => array(
      'idName' => 'accommodationCategory',
      'name_en' => 'Category',
      'editable' => 1,
      'initialTerms' => array(
        array( 'idName' => '1star', 'name_en' => '1 Star' ),
        array( 'idName' => '2stars', 'name_en' => '2 Stars' ),
        array( 'idName' => '3stars', 'name_en' => '3 Stars' ),
        array( 'idName' => '4stars', 'name_en' => '4 Stars' ),
        array( 'idName' => '5stars', 'name_en' => '5 Stars' )
      )
    ),

    'accommodationServices' => array(
      'idName' => 'accommodationServices',
      'name_en' => 'Services',
      'editable' => 1,
      'initialTerms' => array(
        array( 'idName' => 'telephone', 'name_en' => 'Telephone' ),
        array( 'idName' => 'roomservice', 'name_en' => 'Room service' )
      )
    ),

    'accommodationFacilities' => array(
      'idName' => 'accommodationFacilities',
      'name_en' => 'Facilities',
      'editable' => 1,
      'initialTerms' => array(
        array( 'idName' => 'bar', 'name_en' => 'Bar' ),
        array( 'idName' => 'parking', 'name_en' => 'Parking' )
      )
    ),

    'accommodationBrand' => array(
      'idName' => 'accommodationBrand',
      'name_en' => 'Brand',
      'editable' => 1,
      'initialTerms' => array(
        array( 'idName' => 'nh', 'name_en' => 'NH Hotels' ),
        array( 'idName' => 'melia', 'name_en' => 'Meliá' )
      )
    ),

    'accommodationUsers' => array(
      'idName' => 'accommodationUsers',
      'name_en' => 'Users',
      'editable' => 1,
      'initialTerms' => array(
      )
    )

  );
  public function __construct() {}
}