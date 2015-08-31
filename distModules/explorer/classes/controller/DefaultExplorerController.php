<?php

explorer::load('controller/ExplorerController.php');

class DefaultExplorerController extends ExplorerController {

  public function serveMinimal( ) {
    Cogumelo::load('coreModel/DBUtils.php');
    explorer::load('model/GenericExplorerModel.php');
    $resourceModel = new GenericExplorerModel();

    $resources = $resourceModel->listItems( array('fields'=>array('id', 'loc', 'terms') ) );

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = $resource->getAllData('onlydata');


        $row['id'] = $resourceDataArray['id'];

        if( isset($resourceDataArray['loc']) ) {
          $loc = DBUtils::decodeGeometry( $resourceDataArray['loc'] );
          $row['lat'] = $loc['data'][0];
          $row['lng'] = $loc['data'][1];
        }
        unset($resourceDataArray['loc']);

        if( isset($resourceDataArray['terms']) ) {
          $row['terms'] = array_map( 'intval', explode(',',$resourceDataArray['terms']) );
        }


        echo json_encode( $row );

      $coma=',';
    }

    echo ']';

  }

  public function servePartial( ) {
    Cogumelo::load('coreModel/DBUtils.php');
    explorer::load('model/GenericExplorerModel.php');
    $resourceModel = new GenericExplorerModel();

    $resources = $resourceModel->listItems( array('fields'=>array('id', 'title_es', 'image') ) );

var_dump($_POST);

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = $resource->getAllData('onlydata');


        $row['id'] = $resourceDataArray['id'];
        $row['title'] = $resourceDataArray['title_es'];
        $row['image'] = $resourceDataArray['image'];

        echo json_encode( $row );

      $coma=',';
    }

    echo ']';
  }


  public function serveChecksum() {
    echo "CHECKSYM";
  }
}
