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



//
//  SESSION
//
ini_set( 'session.cookie_lifetime', 86400 );
ini_set( 'session.gc_maxlifetime', 86400 );


cogumeloSetSetupValue( 'mod:geozzy:resource:directUrl', 'resource' );

cogumeloSetSetupValue( 'mod:geozzy:resource:urlAliasPatterns',
  array(
    'default' => '/',
    'rtypeAppHotel' => array(
      'default' => '/alojamientos/',
      'gl' => '/aloxamentos/',
      'en' => '/accommodation/'
    ),
    'rtypeAppRestaurant' => array(
      'default' => '/comidas/',
      'en' => '/food/'
    ),
    'rtypeAppEspazoNatural' => array(
      'default' => '/naturaleza/',
      'gl' => '/natureza/',
      'en' => '/nature/'
    ),
    'rtypeAppLugar' => array(
      'default' => '/rincones/',
      'gl' => '/recunchos/',
      'en' => '/places/'
    )
  )
);

cogumeloSetSetupValue( 'mod:geozzy:resource:collectionTypeRules',
  array(
    'default' => array(
      'multimedia' => array('rtypeUrl', 'rtypeFile'),
      'eventos' => array(),
      'base' => array()
    ),
    'rtypeAppHotel' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppRestaurant' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppEspazoNatural' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'base' => array()
    ),
    'rtypeAppLugar' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'base' => array()
    )
  )
);

// En setup.*
// cogumeloSetSetupValue( 'modName:level1:level2', $value );
// $value = cogumeloGetSetupValue( 'modName:level1:level2' );

// En codigo cogumelo
// Cogumelo::setSetupValue( 'modName:level1:level2', $value );
// $value = Cogumelo::getSetupValue( 'modName:level1:level2' );
