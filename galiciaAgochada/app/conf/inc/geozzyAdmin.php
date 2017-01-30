<?php

Cogumelo::setSetupValue( 'mod:mediaserver:publicConf:javascript:vars:admin:adminMap',
  array(
    'marker' => cogumeloGetSetupValue('publicConf:media') + '/module/admin/img/geozzy_marker.png',
    'defaultLat' => '38',
    'defaultLon' => '-3.7',
    'defaultZoom' => '4'
  )
);
