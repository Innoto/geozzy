<?php

Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class AverageVotesViewModel extends Model {

  static $tableName = 'geozzy_resource_averagevote_view';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'comments' => array(
      'type' => 'SMALLINT'
    ),
    'commentsVotes' => array(
      'type' => 'SMALLINT'
    ),
    'averageVotes' => array(
      'type' => 'INT'
    )
  );

  static $extraFilters = array(
    'idIn' => ' geozzy_resource_averagevote_view.id IN (?) '
  );

  var $notCreateDBTable = true;
  var $deploySQL = array(
    // All Times
    array(
      'version' => 'rextComment#1.3',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_averagevote_view;
        CREATE VIEW geozzy_resource_averagevote_view AS

        SELECT COM.resource as id,
          count(*) as comments,
          count(COM.rate) as commentsVotes,
          ROUND(AVG(COM.rate)) as averageVotes
        FROM geozzy_comment as COM, geozzy_taxonomyterm as TXT

        WHERE COM.type=TXT.id AND TXT.idName="comment" AND published=1

        GROUP BY COM.resource;
      '
    )
    /*
    array(
      'version' => 'rextComment#1.1',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_averagevote_view;
        CREATE VIEW geozzy_resource_averagevote_view AS

        SELECT COM.resource as id,
          count(*) as commentsVotes,
          ROUND(AVG(COM.rate)) as averageVotes
        FROM geozzy_comment as COM, geozzy_taxonomyterm as TXT

        WHERE COM.type=TXT.id AND TXT.idName="comment" AND COM.rate > 0  AND published=1

        GROUP BY COM.resource;
      '
    )*/
  );

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
