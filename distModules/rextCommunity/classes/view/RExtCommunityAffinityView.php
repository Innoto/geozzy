<?php

Cogumelo::load( 'coreView/View.php' );
rextCommunity::load( 'controller/RExtCommunityController.php' );


class RExtCommunityAffinityView extends View {

  // var $commCtrl = false;

  public function __construct( $base_dir = false ) {
    // $this->commCtrl = new RExtCommunityController();

    parent::__construct( $base_dir ); // Esto lanza el accessCheck
  }

  /**
   * Evaluate the access conditions and report if can continue
   * @return bool : true -> Access allowed
   */
  public function accessCheck() {
    $validIp = array(
      '213.60.18.106', // Innoto
      '176.83.204.135', '91.117.124.2', // ITG
      '91.116.191.224', // Zadia
      '127.0.0.1'
    );

    $conectionIP = isset( $_SERVER['HTTP_X_REAL_IP'] ) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];

    return( in_array( $conectionIP, $validIp ) || strpos( $conectionIP, '10.77.' ) === 0 );
  }


  public function prepareAffinity() {
    $result = [
      'status' => 'ok',
      'msg' => 'prepareAffinity executado ;-)'
    ];

    header('Content-Type: text/plain; charset=utf-8');

    $affinityAB = $this->updateAffinityModel();

    // header('Content-Type: application/json; charset=utf-8');
    // echo json_encode( $result );
  }

  public function updateAffinityModel() {
    $baseInfo = [];
    $affinityAB = [];
    $userPosIndex = [];

    $baseModel = new RExtCommunityAffinityBaseViewModel();
    $baseList = $baseModel->listItems();
    while( $baseObj = $baseList->fetch() ) {
      $baseData = $baseObj->getAllData( 'onlydata' );
      if( !isset( $userPosIndex[ $baseData['user'] ] ) ) {
        $userPosIndex[ $baseData['user'] ] = 0;
      }
      if( $userPosIndex[ $baseData['user'] ] > 9 ) {
        continue;
      }
      $baseInfo[ $baseData['user'] ][ $baseData['taxonomyterm'] ] = $userPosIndex[ $baseData['user'] ]++;
    }

    if( count( $baseInfo ) > 0 ) {
      $users = array_keys( $baseInfo );
      $usersDone = [];

      foreach( $users as $aUserId ) {
        // echo "Preparando datos para $aUserId\n";
        $aUserTerms = $baseInfo[ $aUserId ];
        if( is_array( $aUserTerms ) && count( $aUserTerms ) > 0 ) {
          foreach( $baseInfo as $bUserId => $bUserTerms ) {
            if( $aUserId === $bUserId || isset( $affinityAB[ $aUserId ][ $bUserId ] ) ) {
              continue;
            }
            // echo "  Mezclando con $bUserId\n";
            $affinity = 0;
            foreach( $aUserTerms as $aTerm => $aPos ) {
              if( isset( $bUserTerms[ $aTerm ] ) ) {
                // echo "  $aTerm: $aPos - ".$bUserTerms[ $aTerm ]."\n";
                // $diffTerms = 10 - $aPos - $bUserTerms[ $aTerm ];
                // $affinity += ( $diffTerms > 0 ) ? $diffTerms : 1;
                // $affinity += $diffTerms;
                $affinity += 20 - $aPos - $bUserTerms[ $aTerm ];
              }
            }
            $affinityAB[ $aUserId ][ $bUserId ] = $affinityAB[ $bUserId ][ $aUserId ] = $affinity;
          }
        }
      }
    }



    foreach( $affinityAB as $aUserId => $affinityUsers ) {
      arsort( $affinityUsers );
      $affinityUsersCSV = implode( ',', array_slice( array_keys( $affinityUsers ), 0, 128 ) );

      echo "Afins($aUserId) => ". $affinityUsersCSV ."\n";

      $afinModel = new RExtCommunityAffinityUserModel( [ 'id' => $aUserId, 'affinityList' => $affinityUsersCSV ] );
      $afinModel->save();
    }

    return $affinityAB;
  }
}

