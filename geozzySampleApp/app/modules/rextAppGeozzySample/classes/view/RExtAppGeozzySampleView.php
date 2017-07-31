<?php
geozzy::load('view/RExtViewCore.php');


class RExtAppGeozzySampleView extends RExtViewCore implements RExtViewInterface {

  public function __construct( RTypeController $defRTypeCtrl = null ) {
    parent::__construct( $defRTypeCtrl, new rextAppGeozzySample() );
  }

} // class extends RExtViewCore
