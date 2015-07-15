<?php

explorer::load('controller/ExplorerController.php');

class DefaultExplorerController extends ExplorerController {

  public function serveIndexData( ) {
    explorer::load('model/GenericExplorerModel.php');
    $resourceModel = new GenericExplorerModel();

    $resources = $resourceModel->listItems( );

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;




        $resourceDataArray = $resource->getAllData('onlydata');

        $resourceDataArray['terms'] = array_map( 'intval', explode(',',$resourceDataArray['terms']) );

        echo json_encode( $resourceDataArray );

      $coma=',';
    }

    echo ']';

  }

  public function serveCurrentData( ) {

  }

  public function serveFilter() {

  }
}
