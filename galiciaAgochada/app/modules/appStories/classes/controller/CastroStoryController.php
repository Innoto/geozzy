<?php

story::load('controller/StoryController.php');


class CastroStoryController extends StoryController {
  function serveStory( $idName ) {

    Cogumelo::load('coreModel/DBUtils.php');
    // INSTANCIA + CONSULTA
    story::load('model/AllstoryStepsViewModel.php');
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
        $row['relatedResource'] = $resource->getter('relatedResource');

        $row['legend'] = $resource->getter('legend');
        $row['KML'] = $resource->getter('KML');
        $row['drawLine'] = $resource->getter('drawLine');
        $row['mapType'] = $resource->getter('mapType');


        // EVENTS
        $due = strtotime('0000-00-00 00:00:00');
        if( strtotime($resource->getter('initDate')) != $due ) {
          $row['initDate'] = $resource->getter('initDate');
        }
        if( strtotime($resource->getter('endDate')) != $due ) {
          $row['endDate'] = $resource->getter('endDate');
        }

        if( isset($row['endDate']) || isset($row['initDate']) ){
          $row['showTimeline'] = $resource->getter('showTimeline');
        }


        echo json_encode( $row );

      $coma=',';
    }

    echo ']';
  }
}
