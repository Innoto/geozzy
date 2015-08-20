<?php

explorer::load('controller/ExplorerController.php');

class DefaultExplorerController extends ExplorerController {

  public function serveMinimal( ) {
    Cogumelo::load('coreModel/DBUtils.php');
    explorer::load('model/GenericExplorerModel.php');
    $resourceModel = new GenericExplorerModel();

    $resources = $resourceModel->listItems( );

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;


        $resourceDataArray = $resource->getAllData('onlydata');

        if( isset($resourceDataArray['loc']) ) {
          $loc = DBUtils::decodeGeometry( $resourceDataArray['loc'] );
          $resourceDataArray['lat'] = $loc['data'][0];
          $resourceDataArray['lng'] = $loc['data'][1];
        }
        unset($resourceDataArray['loc']);

        if( isset($resourceDataArray['terms']) ) {
          $resourceDataArray['terms'] = array_map( 'intval', explode(',',$resourceDataArray['terms']) );
        }
        else {
          $resourceDataArray['terms'] = "";
        }


        echo json_encode( $resourceDataArray );

      $coma=',';
    }

    echo ']';

  }

  public function servePartial( ) {

  }

  public function serveChecksum() {
    echo "CHECKSYM";
  }
}
