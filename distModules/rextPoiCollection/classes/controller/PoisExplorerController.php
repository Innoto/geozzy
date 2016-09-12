<?php

explorer::load('controller/ExplorerController.php');

class PoisExplorerController extends ExplorerController {

  public function serveMinimal( $updatedFrom = false ) {
    Cogumelo::load('coreModel/DBUtils.php');
    $resourceModel = new PoisExplorerModel();

    $filters= [];
    $filters['resourceMain'] = (int) $_POST['resourceID'];

    $resources = $resourceModel->listItems( array('fields'=>array('id', 'loc','terms'), 'filters'=> $filters ) );

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = $resource->getAllData('onlydata');


        if( isset($resourceDataArray['terms']) ) {
          $row['terms'] = array_map( 'intval', explode(',',$resourceDataArray['terms']) );
        }

        $row['id'] = $resourceDataArray['id'];
        if( isset($resourceDataArray['loc']) ) {
          $loc = DBUtils::decodeGeometry( $resourceDataArray['loc'] );
          $row['lat'] = floatval( $loc['data'][0] );
          $row['lng'] = floatval( $loc['data'][1] );
        }
        unset($resourceDataArray['loc']);

        echo json_encode( $row );

      $coma=',';
    }

    echo ']';

  }

  public function servePartial( ) {
    Cogumelo::load('coreModel/DBUtils.php');
    //appExplorer::load('model/PoisExplorerModel.php');
    $resourceModel = new PoisExplorerModel();

    $filters = array();

    if( isset( $_POST['updatedfrom']) && is_numeric($_POST['updatedfrom'])  )  {
      $filters['updatedfrom'] = gmdate( 'Y-m-d H:i:s', $_POST['updatedfrom'] );
    }

    if( isset($_POST['ids']) ){
      $filters['ids'] = array_map( 'intval',$_POST['ids']);
    }

    $resources = $resourceModel->listItems( array('filters' => $filters ) );
    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = array('id' => $resource->getter('id'), 'title' => $resource->getter('title'),
                                   'shortDescription' => $resource->getter('shortDescription'), 'image' => $resource->getter('image'));


        $row['id'] = $resourceDataArray['id'];
        $row['title'] = ( isset($resourceDataArray['title']) )?$resourceDataArray['title']:false;
        $row['description'] = ( isset($resourceDataArray['shortDescription']) )?$resourceDataArray['shortDescription']:'';
        $row['image'] =  ( isset($resourceDataArray['image']) )?$resourceDataArray['image']:false;

        echo json_encode( $row );

      $coma=',';
    }

    echo ']';
  }

}
