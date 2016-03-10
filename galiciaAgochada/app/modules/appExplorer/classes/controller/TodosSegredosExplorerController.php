<?php

explorer::load('controller/ExplorerController.php');

class TodosSegredosExplorerController extends ExplorerController {

  public function serveMinimal( $updatedFrom = false ) {
    Cogumelo::load('coreModel/DBUtils.php');
    appExplorer::load('model/TodosSegredosExplorerModel.php');
    $resourceModel = new TodosSegredosExplorerModel();


    if( $updatedFrom ) {
      $filters = array('updatedfrom'=> $updatedFrom);
    }
    else {
      $filters = array();
    }



    $resources = $resourceModel->listItems( array('fields'=>array('id', 'rtype', 'loc', 'terms', 'image', 'averagePrice'), 'filters'=> $filters ) );

    $coma = '';

    echo '[';
    $limite_temporal   = 1000;
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

      $limite_temporal--;

      if($limite_temporal <= 0 ) {
        break;
      }
    }

    echo ']';

  }

  public function servePartial( ) {
    Cogumelo::load('coreModel/DBUtils.php');
    appExplorer::load('model/TodosSegredosExplorerModel.php');
    $resourceModel = new TodosSegredosExplorerModel();

    $ids = false;

    if( isset($_POST['ids']) ){
      $ids = array_map( 'intval',$_POST['ids']);
    }

    $resources = $resourceModel->listItems( array('filters' => array( 'ids' => $ids) ) );

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = array('id' => $resource->getter('id'), 'title' => $resource->getter('title'),
                                   'mediumDescription' => $resource->getter('mediumDescription'), 'city' => $resource->getter('city'));


        $row['id'] = $resourceDataArray['id'];
        $row['title'] = ( isset($resourceDataArray['title']) )?$resourceDataArray['title']:false;
        $row['description'] = ( isset($resourceDataArray['mediumDescription']) )?$resourceDataArray['mediumDescription']:false;
        $row['city'] =  ( isset($resourceDataArray['city']) )?$resourceDataArray['city']:false;


        echo json_encode( $row );

      $coma=',';
    }

    echo ']';
  }

}
