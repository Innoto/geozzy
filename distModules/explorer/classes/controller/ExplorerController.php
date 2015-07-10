<?php

abstract class ExplorerController {
  abstract function serveIndexData( );
  abstract function serveCurrentData( );
  abstract function serveFilter( );
}
