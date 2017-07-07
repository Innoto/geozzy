<?php

Cogumelo::setSetupValue( 'mod:mediaserver:publicConf:javascript:vars:admin:adminMap',
  array(
    'marker' => Cogumelo::getSetupValue('publicConf:vars:media') . '/img/formularioMarker.png',
    'defaultLat' => '43.8',
    'defaultLon' => '-99.23',
    'defaultZoom' => '3'
  )
);
