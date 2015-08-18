<?php

abstract class ExplorerController {
  abstract function serveIndex( );
  abstract function serveData( );
  abstract function serveChecksum( );
}
