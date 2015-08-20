<?php

abstract class ExplorerController {
  abstract function serveMinimal( );
  abstract function servePartial( );
  abstract function serveChecksum( );
}
