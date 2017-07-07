<?php

$conf->setSetupValue( 'memcached:expirationTime', 15 );
$conf->setSetupValue( 'memcached:hostArray', array(
  'localhost' => array( 'host' => 'localhost', 'port' => 11211 )
) );
