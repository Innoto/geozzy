<?php

explorer::load('controller/ExplorerController.php');

class XantaresExplorerController extends ExplorerController {

  public function serveMinimal( $updatedFrom = false ) {
    Cogumelo::load('coreModel/DBUtils.php');
    explorer::load('model/XantaresExplorerModel.php');
    $resourceModel = new XantaresExplorerModel();


    if( $updatedFrom ) {
      $filters = array('updatedfrom'=> $updatedFrom);
    }
    else {
      $filters = array();
    }



    $resources = $resourceModel->listItems( array('fields'=>array('id', 'rtype', 'loc', 'terms', 'image', 'averagePrice'), 'filters'=> $filters ) );

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = $resource->getAllData('onlydata');


        $row['id'] = $resourceDataArray['id'];
        $row['rtype'] = $resourceDataArray['rtype'];
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
        if( isset($resourceDataArray['averagePrice']) ) {
          $row['averagePrice'] = $resourceDataArray['averagePrice'];
        }



        echo json_encode( $row );

      $coma=',';
    }

    echo ']';

  }

  public function servePartial( ) {
    Cogumelo::load('coreModel/DBUtils.php');
    explorer::load('model/XantaresExplorerModel.php');
    $resourceModel = new XantaresExplorerModel();

    $ids = false;

    if( isset($_POST['ids']) ){
      $ids = array_map( 'intval',$_POST['ids']);
    }

    $resources = $resourceModel->listItems( array('fields'=>array('id', 'title_es', 'shortDescription_es'), 'filters' => array( 'ids' => $ids) ) );

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = $resource->getAllData('onlydata');


        $row['id'] = $resourceDataArray['id'];
        $row['title'] = $resourceDataArray['title_es'];
        $row['description'] = $resourceDataArray['shortDescription_es'];




        echo json_encode( $row );

      $coma=',';
    }

    echo ']';
  }

}