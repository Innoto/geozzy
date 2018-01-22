<?php

explorer::load('controller/ExplorerController.php');

class DefaultExplorerController extends ExplorerController {

  public function serveMinimal( $updatedFrom = false ) {
    Cogumelo::load('coreModel/DBUtils.php');
    explorer::load('model/GenericExplorerModel.php');
    $resourceModel = new GenericExplorerModel();


    if( $updatedFrom ) {
      $filters = array('updatedfrom'=> $updatedFrom);
    }
    else {
      $filters = array();
    }



    $resources = $resourceModel->listItems( array('fields'=>array('id', 'loc', 'terms', 'image'), 'filters'=> $filters, 'cache' => $this->cacheQuery ) );

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = $resource->getAllData('onlydata');


        $row['id'] = $resourceDataArray['id'];

        if( isset($resourceDataArray['loc']) ) {
          $loc = DBUtils::decodeGeometry( $resourceDataArray['loc'] );
          $row['lat'] = floatval( $loc['data'][0] );
          $row['lng'] = floatval( $loc['data'][1] );
        }
        unset($resourceDataArray['loc']);

        if( isset($resourceDataArray['terms']) ) {
          $row['terms'] = array_map( 'intval', explode(',',$resourceDataArray['terms']) );
        }

        if( isset($resourceDataArray['image']) ) {
          $row['img'] = $resourceDataArray['image'];
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

    $filters = array();

    if( isset($_POST['ids']) ){
      $filters['ids'] = array_map( 'intval',$_POST['ids']);
    }

    $resources = $resourceModel->listItems( array('filters' => $filters, 'cache' => $this->cacheQuery ) );
    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = $resource->getAllData('onlydata');


        $row['id'] = $resourceDataArray['id'];


        if( isset($row['title'] ) ) {
          $row['title'] = $resourceDataArray['title_es'];
        }
        if( isset( $resourceDataArray['shortDescription_es']) ) {
          $row['description'] = $resourceDataArray['shortDescription_es'];
        }





        echo json_encode( $row );

      $coma=',';
    }

    echo ']';
  }

}
