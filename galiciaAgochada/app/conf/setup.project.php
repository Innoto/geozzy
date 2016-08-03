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
  cogumeloSetSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = cogumeloGetSetupValue( 'mod:nombreModulo:level1:level2' );

  En código cogumelo:
  Cogumelo::setSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = Cogumelo::getSetupValue( 'mod:nombreModulo:level1:level2' );
*/


//
// Public Access User
//
define( 'GA_ACCESS_USER', 'gaUser' );
define( 'GA_ACCESS_PASSWORD', 'gz15005' );




//
// Lang
//
cogumeloSetSetupValue( 'lang', array(
  'available' => array(
    'es' => array(
      'i18n' => 'es_ES',
      'name' => 'castellano' ),
    'gl' => array(
      'i18n' => 'gl_ES',
      'name' => 'galego' ),
    'en' => array(
      'i18n' => 'en_US',
      'name' => 'english' ),
  ),
  'default' => 'es'
));


//
// URL alias controller: Fichero que contiene una clase UrlAliasController con un metodo getAlternative
//
cogumeloSetSetupValue( 'urlAliasController:classFile', COGUMELO_DIST_LOCATION.'/distModules/geozzy/classes/controller/UrlAliasController.php' );

//
// Error404 en una URL.
//   View = View que se muestra como aviso del error
//
cogumeloSetSetupValue( 'urlError404:view', 'MasterView::page404' );


//
//  Module load
//
global $C_ENABLED_MODULES;
$C_ENABLED_MODULES = array(
  'cogumeloSession',
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'common',
  'devel',
  'user',
  'geozzyAPI',
  'filedata',
  'geozzy',
  'appResourceBridge',
  'adminBI',
  'biMetrics',
  'admin',
  'form',
  'Blocks',
  'table',
  'explorer',
  'appExplorer',
  // testing module
  'testData',
);

// resource Extenssions
global $C_REXT_MODULES;
$C_REXT_MODULES = array(
  'rextAccommodation',
  'rextEatAndDrink',
  'rextContact',
  'rextBI',
  'rextMap',
  'rextMapDirections',
  'rextUrl',
  'rextView',
  'rextFile',
  'rextUserProfile',
  'rextAppLugar',
  'rextAppEspazoNatural',
  'rextAppZona',
  'rextSocialNetwork',
  'rextEvent',
  'rextEventCollection',
  'rextAppFesta',
  'rextPoi',
  'rextPoiCollection',
  'rextComment',
  'rextFavourite',
  'rextRoutes',
  'rextReccommended',
  'rextStoryStep',
  'rextStory',
  //'rextParticipation',
  'rextAudioguide'
);

// resource Types
global $C_RTYPE_MODULES;
$C_RTYPE_MODULES = array(
  'rtypeAppHotel',
  'rtypeAppRestaurant',
  'rtypeUrl',
  'rtypePage',
  'rtypeFile',
  'rtypeAppRuta',
  'rtypeAppLugar',
  'rtypeAppEspazoNatural',
  'rtypeAppFesta',
  'rtypeAppUser',
  'rtypeFavourites',
  'rtypePoi',
  'rtypeEvent',
  'rtypeStory',
  'rtypeStoryStep'
);


// Ultimate modules
global $C_ULTIMATE_MODULES;
$C_ULTIMATE_MODULES = array(
  'initResources',
  'geozzyUser'
);

// Merge all modules
$C_ENABLED_MODULES = array_merge( $C_ENABLED_MODULES, $C_REXT_MODULES, $C_RTYPE_MODULES, $C_ULTIMATE_MODULES );


// Before app/Cogumelo.php execution
// Needed for modules with their own URLs
global $C_INDEX_MODULES;
$C_INDEX_MODULES  = array(
  'cogumeloSession',
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'user',
  'geozzyUser',
  'filedata',
  'geozzy',
  'appResourceBridge',
  'form',
  'admin',
  'Blocks',
  'geozzyAPI',
  'testData',
  'initResources',
  'explorer',
  'rextRoutes',
  'rextComment',
  'rextFavourite',
  'rtypeEvent',
  'rtypePoi',
  'rtypeStory',
  'rtypeStoryStep',
  'devel'
); // DEVEL SIEMPRE DE ULTIMO!!!


//
// RTypes de "uso interno"
//
cogumeloSetSetupValue( 'mod:geozzy:resource:systemRTypes', array(
  'rtypeUrl',
  'rtypePage',
  'rtypeStory',
  'rtypeFavourites',
  'rtypeFile',
  'rtypeEvent',
  'rtypePoi'
));


//
// User config
//
cogumeloSetSetupValue( 'mod:geozzyUser', array(
  'profile' => 'rtypeAppUser'
));


//
// Dependences PATH
//
cogumeloSetSetupValue( 'dependences', array(
  'composerPath' => cogumeloGetSetupValue( 'setup:webBasePath' ).'/vendor/composer',
  'bowerPath' => cogumeloGetSetupValue( 'setup:webBasePath' ).'/vendor/bower',
  'manualPath' => cogumeloGetSetupValue( 'setup:webBasePath' ).'/vendor/manual',
  'manualRepositoryPath' => COGUMELO_LOCATION.'/packages/vendorPackages'
));


//
//  Devel Mod
//
cogumeloSetSetupValue( 'mod:devel', array(
  'allowAccess' => true,
  'url' => 'devel',
  'password' => 'develpassword'
));


//
//  i18n
//
cogumeloSetSetupValue( 'i18n', array(
  'path' => cogumeloGetSetupValue( 'setup:appBasePath' ).'/conf/i18n',
  'localePath' => cogumeloGetSetupValue( 'setup:appBasePath' ).'/conf/i18n/locale',
  'gettextUpdate' => true // update gettext files when working in localhost
));


//
//  Media server
//
cogumeloSetSetupValue( 'publicConf:globalVars', array( 'C_LANG', 'C_SESSION_ID' ) );

cogumeloSetSetupValue( 'publicConf:setupFields', array( 'session:lifetime', 'lang:available',
  'lang:default', 'mod:geozzy:resource:directUrl', 'date:timezone', 'user:session:id' ) );

cogumeloSetSetupValue( 'publicConf:vars:langDefault', cogumeloGetSetupValue( 'lang:default' ) );
cogumeloSetSetupValue( 'publicConf:vars:langAvailableIds', array_keys( cogumeloGetSetupValue( 'lang:available' ) ) );
cogumeloSetSetupValue( 'publicConf:vars:mediaJs',
  ( cogumeloGetSetupValue( 'mod:mediaserver:productionMode' ) === true &&
    cogumeloGetSetupValue( 'mod:mediaserver:notCacheJs' ) !== true )
    ? cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:cachePath' )
    : cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:path' ) );
cogumeloSetSetupValue( 'publicConf:vars:media',
  ( cogumeloGetSetupValue( 'mod:mediaserver:productionMode' ) === true )
    ? cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:cachePath' )
    : cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:path' ) );
cogumeloSetSetupValue( 'publicConf:vars:mediaHost', cogumeloGetSetupValue( 'mod:mediaserver:host' ) );
cogumeloSetSetupValue( 'publicConf:vars:site_host', SITE_HOST );

cogumeloSetSetupValue( 'mod:mediaserver:publicConf:javascript',
  cogumeloGetSetupValue( 'publicConf' )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:less',
  cogumeloGetSetupValue( 'publicConf' )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:smarty',
  cogumeloGetSetupValue( 'publicConf' )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:smarty:setupFields',
  array_merge( cogumeloGetSetupValue( 'publicConf:setupFields' ), array('user:session') )
);


//
// Alias por defecto en recursos
//
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
    ),
    'rtypeFavourites' => array(
      'default' => '/favoritos/',
      'gl' => '/favoritos/',
      'en' => '/favourites/'
    ),
    'rtypeAppFesta' => array(
      'default' => '/fiestas/',
      'gl' => '/festas/',
      'en' => '/parties/'
    ),
    'rtypeAppRuta' => array(
      'default' => '/rutas/',
      'gl' => '/rutas/',
      'en' => '/routes/'
    )
  )
);


//
// Tipo de recurso permitido en colecciones de cada tipo de recurso
//
cogumeloSetSetupValue( 'mod:geozzy:resource:collectionTypeRules',
  array(
    'default' => array(
      'multimedia' => array('rtypeUrl', 'rtypeFile'),
      'eventos' => array('rtypeEvent'),
      'poi' => array('rtypePoi'),
      'pasos' => array(),
      'base' => array()
    ),
    'rtypeAppHotel' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeEvent'),
      'poi' => array(),
      'pasos' => array(),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppRestaurant' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeEvent'),
      'poi' => array(),
      'pasos' => array(),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppEspazoNatural' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'poi' => array('rtypePoi'),
      'pasos' => array(),
      'base' => array()
    ),
    'rtypeAppLugar' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'poi' => array(),
      'pasos' => array(),
      'base' => array()
    ),
    'rtypeAppFesta' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeAppFesta', 'rtypeEvent'),
      'poi' => array(),
      'pasos' => array(),
      'base' => array()
    ),
    'rtypeAppRuta' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'poi' => array('rtypePoi'),
      'pasos' => array(),
      'base' => array()
    ),
    'rtypeStory' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'poi' => array(),
      'pasos' => array('rtypeStoryStep'),
      'base' => array()
    )
  )
);



//
//
//
cogumeloSetSetupValue( 'mod:geozzy:resource:commentRules',
  array(
    'default' => array(
      'moderation' => 'all', // none|verified|all
      'anonymous' => false,
      'ctype' => array() // 'comment','suggest'
    ),
    'rtypeAppHotel' => array(
      'moderation' => 'none', // none|verified|all
      'anonymous' => false,
      'ctype' => array('comment','suggest') // 'comment','suggest'
    ),
    'rtypeAppRestaurant' => array(
      'moderation' => 'verified', // none|verified|all
      'anonymous' => false,
      'ctype' => array('suggest') // 'comment','suggest'
    )
  )
);



//
//
//
cogumeloSetSetupValue( 'mod:geozzy:sitemap:ignoreRTypes', array(
  'rtypeUrl',
  'rtypeFile',
  'rtypeFavourites',
  'rtypeEvent'
));
cogumeloSetSetupValue( 'mod:geozzy:sitemap:default:change', 'weekly' );
cogumeloSetSetupValue( 'mod:geozzy:sitemap:rtypePage:change', 'daily' );
cogumeloSetSetupValue( 'mod:geozzy:sitemap:rtypePage:priority', '0.8' );
cogumeloSetSetupValue( 'mod:geozzy:sitemap:rtypeAppFesta:change', 'daily' );
