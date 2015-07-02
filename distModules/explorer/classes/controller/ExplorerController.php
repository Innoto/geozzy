<?php

abstract class ExplorerController {
  var $filters = array();
  var $index = false;
  var $currentData = false;

  abstract function setInitialData( $parameters );
  abstract function setCurrentData( $parameters );

  public function setFilter( $filterId, $values ) {
    $this->filters[$filterId] = $values;
  }

  public function setIndex( $index ) {
    $this->index = $index;
  }

  public function getInitialData( ) {
    return array('filters'=> $this->filters, 'index'=> $this->index);
  }

  public getCurrentData() {
    return $this->currentData;
  }
}
