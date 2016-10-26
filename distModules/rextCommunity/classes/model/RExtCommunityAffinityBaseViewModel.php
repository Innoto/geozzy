<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class RExtCommunityAffinityBaseViewModel extends Model {

  static $tableName = 'geozzy_resource_rext_community_affinity_base_view';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'taxonomyterm' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomytermModel',
      'key'=> 'id'
    ),
    'points' => array(
      'type' => 'INT'
    )
  );



  static $extraFilters = array(
    'userIn' => ' geozzy_resource_rext_community_affinity_base_view.user IN (?) ',
  );



  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.0',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_rext_community_affinity_base_view;
        CREATE VIEW geozzy_resource_rext_community_affinity_base_view AS
          SELECT
            TAX.id AS id, FAV.user AS user, TAX.taxonomyterm AS taxonomyterm, count(TAX.taxonomyterm) AS points
          FROM
            geozzy_favourites_view FAV, geozzy_resource_taxonomyterm TAX, geozzy_resource_rext_community CM
          WHERE
            FAV.resource = TAX.resource AND CM.share = 1 AND CM.user = FAV.user
          GROUP BY
            FAV.user, TAX.taxonomyterm
          ORDER BY user,points DESC
      '
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}

