<?php

Cogumelo::setSetupValue( 'mod:mediaserver:publicConf:javascript:vars:admin:adminMap',
  array(
    'marker' => cogumeloGetSetupValue('publicConf:vars:media') . '/module/admin/img/geozzy_marker.png',
    'defaultLat' => '42.8',
    'defaultLon' => '-7.8',
    'defaultZoom' => '7'
  )
);
