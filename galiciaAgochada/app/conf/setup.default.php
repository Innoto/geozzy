<?php
/**
 * Previamente (en index.php) ya se definen los siguientes valores:
 *
 * WEB_BASE_PATH - Apache DocumentRoot (declarado en index.php)
 * APP_BASE_PATH - App Path (declarado en index.php)
 * SITE_PATH - App Path (declarado en index.php)
 * IS_DEVEL_ENV - Indica si estamos en el entorno de desarrollo (declarado en setup.php)
 *
 *
 * Normas de estilo:
 *
 * * Nombres:
 * - Inicia por MOD_NOMBREMODULO_ para modulos
 * - Finalizan en _PATH para rutas
 *
 * * Valores:
 * - Las rutas no finalizan en /
 * - Las URL no finalizan en /
 */

// En setup.*
// cogumeloSetSetupValue( 'modName:level1:level2', $value );
// $value = cogumeloGetSetupValue( 'modName:level1:level2' );

// En codigo cogumelo
// Cogumelo::setSetupValue( 'modName:level1:level2', $value );
// $value = Cogumelo::getSetupValue( 'modName:level1:level2' );



//
//  SESSION
//
ini_set( 'session.cookie_lifetime', 86400 );
ini_set( 'session.gc_maxlifetime', 86400 );


cogumeloSetSetupValue( 'mod:geozzy:resource:directUrl', 'resource' );

