<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceTaxonomyAllModel extends Model
{
  var $notCreateDBTable = true;
  var $rcSQL = "CREATE VIEW geozzy_resource_taxonomyall AS
                  SELECT geozzy_resource_taxonomyterm.resource AS id,
                         geozzy_resource_taxonomyterm.taxonomyterm AS idTaxterm,
                         geozzy_taxonomyterm.idName AS idNameTaxterm,
                         geozzy_taxonomyterm.name_es AS nameTaxterm_es,
                         geozzy_taxonomyterm.name_gl AS nameTaxterm_gl,
                         geozzy_taxonomyterm.name_en AS nameTaxterm_en,
                         geozzy_taxonomyterm.weight AS weightTaxterm,
                         geozzy_taxonomyterm.taxgroup AS idTaxgroup,
                         geozzy_taxonomygroup.idName AS idNameTaxgroup
                  FROM `geozzy_resource_taxonomyterm`
                  JOIN `geozzy_taxonomygroup` JOIN `geozzy_taxonomyterm`
                  WHERE geozzy_resource_taxonomyterm.taxonomyterm = geozzy_taxonomyterm.id
                  AND geozzy_taxonomyterm.taxgroup = geozzy_taxonomygroup.id ORDER BY geozzy_resource_taxonomyterm.resource;
              ";

  static $tableName = 'geozzy_resource_taxonomyall';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'idTaxterm' => array(
      'type' => 'INT'
    ),
    'idNameTaxterm' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'unique' => true
    ),
    'nameTaxterm' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'weightTaxterm' => array(
      'type' => 'INT'
    ),
    'idTaxgroup' => array(
      'type' => 'INT'
    ),
    'idNameTaxgroup' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'unique' => true
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
