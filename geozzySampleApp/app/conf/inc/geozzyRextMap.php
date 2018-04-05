<?php

Cogumelo::setSetupValue('mod:mediaserver:publicConf:javascript:vars:rextMapConf',
  array(
    'defaultMarker' => Cogumelo::getSetupValue('publicConf:vars:media') . '/img/markerAzul.png',
    'scrollwheel' => false,
    'mapTypeId' => 'roadmap',
    'defaultLat' => '42.494937',
    'defaultLng' => '-7.5071074' ,
    'defaultZoom' => 10 ,
    'styles'  => false
  )
);
