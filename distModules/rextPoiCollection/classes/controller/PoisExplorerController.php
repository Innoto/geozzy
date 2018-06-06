<?php

explorer::load('controller/ExplorerController.php');

class PoisExplorerController extends ExplorerController {

  public function serveMinimal( $updatedFrom = false ) {
    Cogumelo::load('coreModel/DBUtils.php');
    $resourceModel = new PoisExplorerModel();

    $filters= [];
    if( isset( $_POST['resourceID'] ) ) {
      $filters['resourceMain'] = (int) $_POST['resourceID'];
    }

    $lang_default = Cogumelo::getSetupValue( 'lang:default' );

    $resources = $resourceModel->listItems( array('fields'=>array('id','rType','content_'.$lang_default,'loc','isNormalResource','terms'), 'filters'=> $filters, 'cache' => $this->cacheQuery ) );

    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){

        $row = array();
        $resourceDataArray = $resource->getAllData('onlydata');


        if( isset($resourceDataArray['id']) ) {
          echo $coma;
          $row['isNormalResource'] = $resourceDataArray['isNormalResource'];

          if( isset($resourceDataArray['terms']) ) {
            $row['terms'] = array_map( 'intval', explode(',',$resourceDataArray['terms']) );
          }

          $row['id'] = $resourceDataArray['id'];
          $row['rType'] = $resourceDataArray['rType'];

          if( isset($resourceDataArray['loc']) ) {
            $loc = DBUtils::decodeGeometry( $resourceDataArray['loc'] );
            $row['lat'] = floatval( $loc['data'][0] );
            $row['lng'] = floatval( $loc['data'][1] );
          }
          unset($resourceDataArray['loc']);

          if( empty($resourceDataArray['isNormalResource']) && !empty($resourceDataArray['content_'.$lang_default  ]) ){
            $pitchYaw = explode( "/", $resourceDataArray['content_'.$lang_default ]);
            $row['panoramaYaw'] = $pitchYaw[1];
            $row['panoramaPitch'] = $pitchYaw[0];
          }

          echo json_encode( $row );
          $coma=',';
        }





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

    $resources = $resourceModel->listItems( array('filters' => $filters, 'cache' => $this->cacheQuery ) );
    $coma = '';

    echo '[';

    while( $resource = $resources->fetch() ){
        echo $coma;
        $row = array();

        $resourceDataArray = array('id' => $resource->getter('id'), 'title' => $resource->getter('title'),
        'shortDescription' => $resource->getter('shortDescription'), 'mediumDescription' => $resource->getter('mediumDescription'),
        'image' => $resource->getter('image'));

        $row['id'] = $resourceDataArray['id'];
        $row['title'] = ( isset($resourceDataArray['title']) )?$resourceDataArray['title']:false;
        $row['description'] = '';
        if( !empty( $resourceDataArray['shortDescription'] ) ) {
          $row['description'] =$resourceDataArray['shortDescription'];
        }
        elseif( !empty( $resourceDataArray['mediumDescription'] ) ) {
          $row['description'] = $resourceDataArray['mediumDescription'];
        }
        $row['image'] =  ( isset($resourceDataArray['image']) )?$resourceDataArray['image']:false;

        echo json_encode( $row );

      $coma=',';
    }

    echo ']';
  }

}
