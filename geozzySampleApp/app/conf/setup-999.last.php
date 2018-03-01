<?php
/*
  Normas de estilo:

  Nombres:
  - Inicia por mod:nombreModulo: para configuración de modulos
  - Fuera de módulos, de forma general, usaremos tema:subtema:variable
  - Usar nombres finalizados en "Path" (variablePath) para rutas

  Valores:
  - Las rutas NO finalizan en /
  - Las URL NO finalizan en /


  Llamadas a metodos:

  En ficheros de setup:
  $conf->setSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = $conf->getSetupValue( 'mod:nombreModulo:level1:level2' );

  En código cogumelo:
  Cogumelo::setSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = Cogumelo::getSetupValue( 'mod:nombreModulo:level1:level2' );
*/





//
//  Media server - SIEMPRE AL FINAL!!!
//
$conf->mergeSetupValue( 'publicConf:globalVars', [ 'C_LANG', 'C_SESSION_ID' ] );

$conf->mergeSetupValue( 'publicConf:setupFields', [
  'google:maps:key', 'google:analytics:key', 'google:recaptcha:key:site',
  'session:lifetime', 'lang:available', 'lang:default', 'date:timezone',
  'socialNetworks', 'mod:geozzy:resource:directUrl', 'mod:admin:defaultURL',
]);

$conf->setSetupValue( 'publicConf:vars:langDefault', $conf->getSetupValue( 'lang:default' ) );
$conf->setSetupValue( 'publicConf:vars:langAvailableIds', array_keys( $conf->getSetupValue( 'lang:available' ) ) );

$conf->setSetupValue( 'publicConf:vars:mediaJs',
  ( $conf->getSetupValue( 'mod:mediaserver:productionMode' ) === true &&
    $conf->getSetupValue( 'mod:mediaserver:notCacheJs' ) !== true )
    ? $conf->getSetupValue( 'mod:mediaserver:host' ).$conf->getSetupValue( 'mod:mediaserver:cachePath' )
    : $conf->getSetupValue( 'mod:mediaserver:host' ).$conf->getSetupValue( 'mod:mediaserver:path' ) );

$conf->setSetupValue( 'publicConf:vars:media',
  ( $conf->getSetupValue( 'mod:mediaserver:productionMode' ) === true )
    ? $conf->getSetupValue( 'mod:mediaserver:host' ).$conf->getSetupValue( 'mod:mediaserver:cachePath' )
    : $conf->getSetupValue( 'mod:mediaserver:host' ).$conf->getSetupValue( 'mod:mediaserver:path' ) );

$conf->setSetupValue( 'publicConf:vars:mediaHost', $conf->getSetupValue( 'mod:mediaserver:host' ) );
$conf->setSetupValue( 'publicConf:vars:site_host', SITE_HOST );

$conf->mergeSetupValue( 'mod:mediaserver:publicConf:javascript:globalVars', $conf->getSetupValue( 'publicConf:globalVars' ) );
$conf->mergeSetupValue( 'mod:mediaserver:publicConf:javascript:setupFields', $conf->getSetupValue( 'publicConf:setupFields' ) );
$conf->mergeSetupValue( 'mod:mediaserver:publicConf:javascript:vars', $conf->getSetupValue( 'publicConf:vars' ) );

$conf->mergeSetupValue( 'mod:mediaserver:publicConf:less:globalVars', $conf->getSetupValue( 'publicConf:globalVars' ) );
$conf->mergeSetupValue( 'mod:mediaserver:publicConf:less:setupFields', $conf->getSetupValue( 'publicConf:setupFields' ) );
$conf->mergeSetupValue( 'mod:mediaserver:publicConf:less:vars', $conf->getSetupValue( 'publicConf:vars' ) );

$conf->mergeSetupValue( 'mod:mediaserver:publicConf:smarty:globalVars', $conf->getSetupValue( 'publicConf:globalVars' ) );
$conf->mergeSetupValue( 'mod:mediaserver:publicConf:smarty:setupFields', $conf->getSetupValue( 'publicConf:setupFields' ) );
$conf->mergeSetupValue( 'mod:mediaserver:publicConf:smarty:vars', $conf->getSetupValue( 'publicConf:vars' ) );

$conf->addSetupValue( 'mod:mediaserver:publicConf:smarty:setupFields', 'user:session' );
//
//  Media server - SIEMPRE AL FINAL!!!
//
