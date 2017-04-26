<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceMultimediaViewModel extends Model {

  static $tableName = 'geozzy_resource_multimedia_view';

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
    'rTypeId' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourcetypeModel',
      'key'=> 'id'
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'userUpdate' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'published' => array(
      'type' => 'BOOLEAN'
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'shortDescription' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'image' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir' => '/Resource/'
    ),
    'imageName' => [
      'type' => 'VARCHAR',
      'size' => 250
    ],
    'imageAKey' => [
      'type' => 'VARCHAR',
      'size' => 16
    ],
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'timeLastUpdate' => array(
      'type' => 'DATETIME'
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    ),
    'author' => array(
      'type' => 'VARCHAR',
      'size' => 500
    ),
    'file' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir'=> '/RExtFile/'
    ),
    'embed' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'url' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    )
  );

  static $extraFilters = array(
    'inId' => ' geozzy_resource_multimedia_view.id IN (?) '
  );

  var $notCreateDBTable = true;



  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.93',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_multimedia_view;
        CREATE VIEW geozzy_resource_multimedia_view AS
          SELECT
            r.id, r.idName, r.rTypeId, r.user, r.userUpdate, r.published,
            {multilang:r.title_$lang,}
            {multilang:r.shortDescription_$lang,}

            r.image, fdr.name AS imageName, fdr.AKey AS imageAKey,

            r.timeCreation, r.timeLastUpdate, r.weight,
            rf.author AS author,

            rf.file AS file, fdru.name AS fileName, fdru.AKey AS fileAKey,

            NULL AS embed, NULL AS url
          FROM
            ((((
            geozzy_resource r
            join geozzy_resource_rext_file rf)
            join geozzy_resourcetype rtype)
            LEFT JOIN filedata_filedata AS fdr ON r.image = fdr.id)
            LEFT JOIN filedata_filedata AS fdru ON rf.file = fdru.id)
          WHERE
            rtype.id = r.rTypeId AND rtype.idName = "rtypeFile"
            AND r.id = rf.resource

          UNION ALL

          SELECT
            r.id, r.idName, r.rTypeId, r.user, r.userUpdate, r.published,
            {multilang:r.title_$lang,}
            {multilang:r.shortDescription_$lang,}

            r.image, fdr.name AS imageName, fdr.AKey AS imageAKey,

            r.timeCreation, r.timeLastUpdate, r.weight,
            ru.author AS author,

            NULL AS file, NULL AS fileName, NULL AS fileAKey,

            ru.embed AS embed, ru.url AS url
          FROM
            (((
            geozzy_resource r
            join geozzy_resource_rext_url ru)
            join geozzy_resourcetype rtype)
            LEFT JOIN filedata_filedata AS fdr ON r.image = fdr.id)
          WHERE
            rtype.id = r.rTypeId AND rtype.idName = "rtypeUrl"
            AND r.id = ru.resource;
      '
    ),
    array(
      'version' => 'geozzy#1.6',
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_multimedia_view;

        CREATE VIEW geozzy_resource_multimedia_view AS

          SELECT r.id, r.idName, r.rTypeId, r.user, r.userUpdate, r.published,
            {multilang:r.title_$lang,}
            {multilang:r.shortDescription_$lang,}
            r.image, r.timeCreation, r.timeLastUpdate, r.weight,
            rf.author AS author, rf.file, NULL AS embed, NULL AS url
          FROM geozzy_resource AS r, geozzy_resource_rext_file AS rf, geozzy_resourcetype as rtype
          WHERE rtype.id = r.rTypeId AND rtype.idName = "rtypeFile"
            AND r.id = rf.resource

          UNION ALL

          SELECT r.id, r.idName, r.rTypeId, r.user, r.userUpdate, r.published,
            {multilang:r.title_$lang,}
            {multilang:r.shortDescription_$lang,}
            r.image, r.timeCreation, r.timeLastUpdate, r.weight,
            ru.author AS author, NULL AS file, ru.embed, ru.url
          FROM geozzy_resource AS r, geozzy_resource_rext_url AS ru, geozzy_resourcetype as rtype
          WHERE rtype.id = r.rTypeId AND rtype.idName = "rtypeUrl"
            AND r.id = ru.resource;
      '
    ),
    array(
      'version' => 'geozzy#1.4',
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_multimedia_view;

        CREATE VIEW geozzy_resource_multimedia_view AS

          SELECT r.id, r.rTypeId, r.user, r.userUpdate, r.published,
            {multilang:r.title_$lang,}
            {multilang:r.shortDescription_$lang,}
            r.image, r.timeCreation, r.timeLastUpdate, r.weight,
            rf.author AS author, rf.file, NULL AS embed, NULL AS url
          FROM geozzy_resource AS r, geozzy_resource_rext_file AS rf, geozzy_resourcetype as rtype
          WHERE rtype.id = r.rTypeId AND rtype.idName = "rtypeFile"
            AND r.id = rf.resource

          UNION ALL

          SELECT r.id, r.rTypeId, r.user, r.userUpdate, r.published,
            {multilang:r.title_$lang,}
            {multilang:r.shortDescription_$lang,}
            r.image, r.timeCreation, r.timeLastUpdate, r.weight,
            ru.author AS author, NULL AS file, ru.embed, ru.url
          FROM geozzy_resource AS r, geozzy_resource_rext_url AS ru, geozzy_resourcetype as rtype
          WHERE rtype.id = r.rTypeId AND rtype.idName = "rtypeUrl"
            AND r.id = ru.resource;
      '
    ),
    array(
      'version' => 'geozzy#1.0',
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_multimedia_view;

        CREATE VIEW geozzy_resource_multimedia_view AS

          SELECT r.id, r.rTypeId, r.user, r.userUpdate, r.published,
            {multilang:r.title_$lang,}
            {multilang:r.shortDescription_$lang,}
            r.image, r.timeCreation, r.timeLastUpdate, r.averageVotes, r.weight,
            rf.author AS author, rf.file, NULL AS embed, NULL AS url
          FROM geozzy_resource AS r, geozzy_resource_rext_file AS rf, geozzy_resourcetype as rtype
          WHERE rtype.id = r.rTypeId AND rtype.idName = "rtypeFile"
            AND r.id = rf.resource

          UNION ALL

          SELECT r.id, r.rTypeId, r.user, r.userUpdate, r.published,
            {multilang:r.title_$lang,}
            {multilang:r.shortDescription_$lang,}
            r.image, r.timeCreation, r.timeLastUpdate, r.averageVotes, r.weight,
            ru.author AS author, NULL AS file, ru.embed, ru.url
          FROM geozzy_resource AS r, geozzy_resource_rext_url AS ru, geozzy_resourcetype as rtype
          WHERE rtype.id = r.rTypeId AND rtype.idName = "rtypeUrl"
            AND r.id = ru.resource;
      '
    )
  );

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
