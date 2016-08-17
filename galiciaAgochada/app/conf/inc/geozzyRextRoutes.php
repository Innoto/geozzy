<?php

cogumeloSetSetupValue('mod:mediaserver:publicConf:javascript:vars:rextRoutesConf',
  array(
    'cacheTime' => 3600, // cache in server memory routes time (in seconds)
    'newMapHeight' => 450,
    'strokeColor' => '#EF7C1F',
    'strokeBorderColor' => '#FFFFFF',
    'strokeOpacity' => 1,
    'strokeWeight' => 3,
    'strokeBorderWeight' => 9,

    'markerStart' => array('img'=>'/module/rextRoutes/img/markerStart.png', 'anchor'=>[3,40]),
    'markerEnd' => array('img'=>'/module/rextRoutes/img/markerEnd.png', 'anchor'=>[ 3,40]),
    'markerTrack' => array('img'=>'', 'anchor'=>[])
  )
);
