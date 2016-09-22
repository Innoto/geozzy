<?php

rtypeStory::load('controller/StoryController.php');


class CastroStoryController extends StoryController {
  function serveStory( $idName ) {

    // INSTANCIA + CONSULTA
    rtypeStoryStep::load('model/AllstoryStepsViewModel.php');
    $resourceModel = new AllstoryStepsViewModel();
    $resources = $resourceModel->listItems( ['filters'=>['storyName'=>'castroStory']] );

    // FORMACION DA ESTRUCTURA FINAL DE DATOS
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

        $row['defaultZoom'] = $resource->getter('defaultZoom');
        $row['title'] = $resource->getter('title');
        $row['shortDescription'] = $resource->getter('shortDescription');
        $row['mediumDescription'] = $resource->getter('mediumDescription');



        echo json_encode( $row );

      $coma=',';
    }

    echo ']';
  }
}
