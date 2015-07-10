<?php

explorer::load('controller/ExplorerController.php');

class DefaultExplorerController extends ExplorerController {

  public function serveIndexData( ) {
    geozzy::load('model/ResourceModel.php');
    $resourceModel = new ResourceModel();

    $resources = $resourceModel->listItems(
                                      array(
                                        'filters' => array('published' => 1),
                                        'fields' => array('id', 'type', 'latlng')

                                      )
                                    );


    echo json_encode( $resources->fetch()->getAllData('onlydata') ) ;
  }

  public function serveCurrentData( ) {

  }

  public function serveFilter() {

  }
}
