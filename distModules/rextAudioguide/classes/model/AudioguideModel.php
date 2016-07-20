<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );

class AudioguideModel extends Model
{
  static $tableName = 'geozzy_resource_rext_audioguide';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'audioFile' => array(
      'type'=>'INT',
      'key' => 'id',
      'multilang' => true,
      //Gardamos o id do ficheiro en cada idioma na carpeta definida, e procesamos a dependencia cos ficheiros a man
      'uploadDir'=> '/RExtAudioFiles/'
    ),
    'distance' => array(
      'type' => 'INT'
    )
  );

  var $deploySQL = array(
    array(
      'version' => 'rextAudioguide#1.1',
      'sql'=> '
      CREATE TABLE geozzy_resource_rext_audioguide (
        `id` INT NOT NULL auto_increment,
        `resource` INT,

        {multilang:`audioFile_$lang` INT,}

        `distance` INT,
        PRIMARY KEY USING BTREE (`id`),
        INDEX (`resource`)
      ) ENGINE = InnoDB AUTO_INCREMENT = 10 DEFAULT CHARSET = utf8 COLLATE = utf8_general_ci ;'
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
