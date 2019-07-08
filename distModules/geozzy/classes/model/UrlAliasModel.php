<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class UrlAliasModel extends Model {

  static $tableName = 'geozzy_url_alias';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'http' => array(
      'type' => 'CHAR',
      'size' => 4
    ),
    'canonical' => array(
      'type' => 'BOOLEAN'
    ),
    'lang' => array(
      'type' => 'CHAR',
      'size' => 4
    ),
    'urlFrom' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'urlTo' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    ),
    'label' => array(
      'type' => 'VARCHAR',
      'size' => 100
    )
  );

  static $extraFilters = array(
    'resourceIn' => ' geozzy_url_alias.resource IN (?) ',
    'resourceNotIn' => ' geozzy_url_alias.resource NOT IN (?) ',
    'resourceNot' => ' geozzy_url_alias.resource <> ? ',
  );



  var $deploySQL = array(
    array(
      'version' => 'geozzy#1.95',
      'sql'=> '
        ALTER TABLE geozzy_url_alias
        ADD COLUMN label VARCHAR(100) NULL
      '
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }


  public function deleteByResource( $resId ) {
    $result = false;

    $urlList = $this->listItems([
      'filters' => ['resource' => $resId],
      'fields' => ['id', 'resource', 'urlFrom']
    ]);
    if( is_object( $urlList ) ) {
      while( $urlObj = $urlList->fetch() ) {
        $result = true;
        Cogumelo::debug( __METHOD__.' resId:'.$resId.' urlFrom:'.$urlObj->getter('urlFrom') );
        $urlObj->delete();
      }
    }

    return( $result );
  }
}
