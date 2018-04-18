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
//  Mail sender
//
// $conf->setSetupValue( 'mail', array(
//   'type' => 'smtp',
//   'host' => 'smtp.gmail.com',
//   'port' => '587',
//   'auth' => true,
//   'user' => 'usuario@gmail.com',
//   'pass' => 'contraseña',
//   'secure' => 'tls',
//   'fromName' => 'GeozzySampleApp Mail',
//   'fromEmail' => 'usuario@gmail.com'
// ));


//
// Google keys
//
// $conf->setSetupValue( 'google:analytics:key', 'UA-.......-1' );
// $conf->setSetupValue( 'google:maps:key', 'A..................................Q' );
// $conf->setSetupValue( 'google:recaptcha:key:site', '6..................................T' );
// $conf->setSetupValue( 'google:recaptcha:key:secret', '6..................................d' );



//
// URL alias controller: Fichero que contiene una clase UrlAliasController con un metodo getAlternative
//
$conf->createSetupValue( 'urlAliasController:classFile',
  COGUMELO_DIST_LOCATION.'/distModules/geozzy/classes/controller/UrlAliasController.php' );


//
// Error404 en una URL.
//   View = View que se muestra como aviso del error
//
$conf->setSetupValue( 'urlError403:view', 'PageErrorView::page403' );
$conf->setSetupValue( 'urlError404:view', 'PageErrorView::page404' );

//
// No se adminten URLs a ficheros solo con su Id
//
$conf->setSetupValue( 'mod:filedata:verifyAKeyUrl', true );


//
//Activación por defecto compartir redes sociales
//
// $conf->setSetupValue( 'shareSocialNetwork:default',true );


//
// Admin.
//
$conf->setSetupValue( 'mod:admin:logoPath', '/img/logo.png' );
$conf->setSetupValue( 'mod:admin:titlePath', 'GeozzySampleApp Admin' );
// $conf->setSetupValue( 'mod:admin:menuClosed', true );
// $conf->setSetupValue( 'mod:admin:defaultURL', 'topic/10' );


//
//  Module load
//
global $C_ENABLED_MODULES;
$C_ENABLED_MODULES = [
  'cogumeloSession',
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'common',
  'devel',
  // 'develWebPanel',
  'user',
  'filedata',
  'geozzy',
  'appResourceBridge',
  'admin',
  'form',
  'table',
];

// resource Extenssions
global $C_REXT_MODULES;
$C_REXT_MODULES = [
  'rextMapDirections',
  'rextUrl',
  'rextView',
  'rextFile',
  'rextContact',
  'rextMap',
  'rextSocialNetwork',
  'rextComment',
  'rextAudioguide',
  'rextAppGeozzySample',
];

// resource Types
global $C_RTYPE_MODULES;
$C_RTYPE_MODULES = [
  'rtypePage',
  'rtypeUrl',
  'rtypeFile',
  'rtypeAppGeozzySample',
];

// resource Types
global $C_ULTIMATE_MODULES;
$C_ULTIMATE_MODULES = [
  'geozzyAPI',
  'initResources',
  'geozzyUser',
];

// Merge all modules
$C_ENABLED_MODULES = array_merge( $C_ENABLED_MODULES, $C_REXT_MODULES, $C_RTYPE_MODULES, $C_ULTIMATE_MODULES );


// before app/Cogumelo.php execution
// Needed for modules with their own urls
global $C_INDEX_MODULES;
$C_INDEX_MODULES = [
  'cogumeloSession',
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'user',
  'geozzyUser',
  'filedata',
  'geozzy',
  'form',
  'admin',
  'geozzyAPI',
  'appResourceBridge',
  'initResources',
  'rextComment',
  // 'devel',
]; // devel SIEMPRE DE ULTIMO!!!


//
// RTypes de "uso interno"
//
$conf->setSetupValue( 'mod:geozzy:resource:systemRTypes', [
  'rtypeUrl',
  'rtypePage',
  'rtypeFile',
]);


// USER PROFILE
// $conf->setSetupValue( 'mod:geozzyUser:profile', '' );
// $conf->setSetupValue( 'mod:geozzyUser:recoveryPasswordRedirect', '/asdf' );


//
// Dependences PATH
//
$conf->setSetupValue( 'dependences', [
  'composerPath' => $conf->getSetupValue( 'setup:webBasePath' ).'/vendor/composer',
  'yarnPath' => $conf->getSetupValue( 'setup:webBasePath' ).'/vendor/yarn',
  'bowerPath' => $conf->getSetupValue( 'setup:webBasePath' ).'/vendor/bower',
  'manualPath' => $conf->getSetupValue( 'setup:webBasePath' ).'/vendor/manual',
  'manualRepositoryPath' => COGUMELO_LOCATION.'/packages/vendorPackages'
]);


//
//  i18n
//
$conf->setSetupValue( 'i18n', [
  'path' => $conf->getSetupValue( 'setup:appBasePath' ).'/conf/i18n',
  'localePath' => $conf->getSetupValue( 'setup:appBasePath' ).'/conf/i18n/locale',
  'gettextUpdate' => true // update gettext files when working in localhost
]);


//
// Reglas para collections
//
$conf->setSetupValue( 'mod:geozzy:resource:collectionTypeRules', [
  'default' => [
    'default' => [
      'listOptions' => [],
      'manualCreate' => [ 'rtypeUrl' ],
      'widget' => []
    ],
    'multimedia' => [
      'listOptions' => [],
      'manualCreate' => [ 'rtypeUrl', 'rtypeFile' ],
      'widget' => ['fileMultiple']
    ]
  ]
]);

//
// Establecemos valores comunes para perfiles (colecciones tipo multimedia)
//
// $conf->setSetupValue( 'collections:imageProfile', [
//   'default' => 'wmdpi4',
//   'multimediaThumbnail' => 'imgMultimediaGallery',
//   'multimediaLong' => 'imageMultimediaLarge'
// ] );


//
// Reglas para comentarios
//
$conf->setSetupValue( 'mod:geozzy:resource:commentRules',
  array(
    'default' => array(
      'moderation' => 'all', // none|verified|all
      'anonymous' => false,
      'ctype' => array() // 'comment','suggest'
    ),
  )
);


//
// Alias por defecto en recursos
//
$conf->setSetupValue( 'mod:geozzy:resource:urlAliasPatterns', [
  'default' => '/',
  'rtypeUrl' => [ 'default' => '/url/' ],
  'rtypeFile' => [ 'default' => '/file/' ],
]);


//
// Limitando el contenido del sitemap.xml
//
$conf->setSetupValue( 'mod:geozzy:sitemap:ignoreRTypes', [
  'rtypeUrl',
  'rtypeFile',
]);
$conf->setSetupValue( 'mod:geozzy:sitemap:regexUrlDeny', [
  '#/INTERNO[-_]#',
  '#^(/..)?/userverified/#',
  '#^(/..)?/userprofile#',
]);
// $conf->setSetupValue( 'mod:geozzy:sitemap:regexUrlAllow', [ '#/politica-#', '#cookies#', '#user#' ] );
// $conf->setSetupValue( 'mod:geozzy:sitemap:disable', true );



//
//  Filedata Mod: Perfiles de imagen propios del proyecto
//
include 'setup-500.project.filedataImageProfiles.php';



//
//  Media server - SIEMPRE AL FINAL!!!
//

// $conf->setSetupValue( 'publicConf:globalVars', [ 'ALGO' ] );

// $conf->setSetupValue( 'publicConf:setupFields', [ 'mod:geozzy:resource:algo' ] );

// $conf->setSetupValue( 'publicConf:vars:algo', 1234 );

// $conf->addSetupValue( 'mod:mediaserver:publicConf:smarty:setupFields', 'user:session' );

//
//  Media server - SIEMPRE AL FINAL!!!
//  setup-999.last.php repasa los minimos
//
