<?php
admin::load('view/AdminViewMaster.php');


class AdminViewTopic extends AdminViewMaster {

  public function __construct( $base_dir ) {
    parent::__construct($base_dir);
  }

  public function topicsSync() {

    $topicModel = new TopicModel();
    $topics = $topicModel->listItems( [ 'order' => [ 'weight' => 1, 'idName' => 1 ] ] );

    header('Content-type: application/json');
    echo '[';

    $c = '';
    while( $topic = $topics->fetch() ) {
      $topicData = $topic->getAllData();
      echo $c.json_encode( $topicData['data'] );
      if( $c === '' ) {
        $c=',';
      }
    }
    echo ']';
  }
}

