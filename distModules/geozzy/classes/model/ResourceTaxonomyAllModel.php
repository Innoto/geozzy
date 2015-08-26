<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceTaxonomyAllModel extends Model {

  static $tableName = 'geozzy_resource_taxonomyall';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'name' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'parent' => array(
      'type' => 'INT',
    ),
    'weight' => array(
      'type' => 'INT',
    ),
    'taxgroup' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomygroupModel',
      'key' => 'id'
    ),
    'icon' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id'
    ),
    'idNameTaxgroup' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'resource' => array(
      'type' => 'INT'
    ),
    'idResTaxTerm' => array(
      'type' => 'INT'
    ),
    'weightResTaxTerm' => array(
      'type' => 'INT'
    )
  );

  static $extraFilters = array();

  var $notCreateDBTable = true;

  var $rcSQL = 'DROP VIEW IF EXISTS geozzy_resource_taxonomyall; '.
    'CREATE VIEW geozzy_resource_taxonomyall AS '.
      'SELECT '.
        'geozzy_taxonomyterm.id AS id, '.
        'geozzy_taxonomyterm.idName AS idName, '.
        'geozzy_taxonomyterm.name_es AS name_es, '.
        'geozzy_taxonomyterm.name_gl AS name_gl, '.
        'geozzy_taxonomyterm.name_en AS name_en, '.
        'geozzy_taxonomyterm.parent AS parent, '.
        'geozzy_taxonomyterm.weight AS weight, '.
        'geozzy_taxonomyterm.icon AS icon, '.
        'geozzy_taxonomyterm.taxgroup AS taxgroup, '.
        'geozzy_taxonomygroup.idName AS idNameTaxgroup, '.
        'geozzy_resource_taxonomyterm.resource AS resource, '.
        'geozzy_resource_taxonomyterm.id AS idResTaxTerm, '.
        'geozzy_resource_taxonomyterm.weight AS weightResTaxTerm '.
      'FROM `geozzy_resource_taxonomyterm` '.
        'JOIN `geozzy_taxonomygroup` '.
        'JOIN `geozzy_taxonomyterm` '.
      'WHERE geozzy_resource_taxonomyterm.taxonomyterm = geozzy_taxonomyterm.id '.
        'AND geozzy_taxonomyterm.taxgroup = geozzy_taxonomygroup.id '.
      'ORDER BY geozzy_resource_taxonomyterm.resource; ';


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
