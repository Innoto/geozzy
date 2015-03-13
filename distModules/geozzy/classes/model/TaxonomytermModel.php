<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class TaxonomytermModel extends Model
{
  static $tableName = 'geozzy_taxonomyterm';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'parent' => array(
      'type' => 'INT',
    ),
    'text' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'parent' => array(
      'type' => 'INT',
    ),
    'weight' => array(
      'type' => 'INT',
    ),
    '' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomygroupModel',
      'key' => 'id'
    )

  );

  var $filters = array( );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
