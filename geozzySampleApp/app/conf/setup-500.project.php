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
  $conf->createSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = $conf->getSetupValue( 'mod:nombreModulo:level1:level2' );

  En código cogumelo:
  Cogumelo::createSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = Cogumelo::getSetupValue( 'mod:nombreModulo:level1:level2' );
*/



//
//  Mail sender
//
// $conf->createSetupValue( 'mail', array(
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
// $conf->createSetupValue( 'google:analytics:key', 'UA-.......-1' );
// $conf->createSetupValue( 'google:maps:key', 'A..................................Q' );
// $conf->createSetupValue( 'google:recaptcha:key:site', '6..................................T' );
// $conf->createSetupValue( 'google:recaptcha:key:secret', '6..................................d' );


//
// No se adminten URLs a ficheros solo con su Id
//
$conf->createSetupValue( 'mod:filedata:verifyAKeyUrl', true );


//
//Activación por defecto compartir redes sociales
//
// $conf->createSetupValue( 'shareSocialNetwork:default',true );


//
// Admin.
//
$conf->createSetupValue( 'mod:admin:logoPath', '/img/logo.png' );
$conf->createSetupValue( 'mod:admin:titlePath', 'GeozzySampleApp Admin' );
// $conf->createSetupValue( 'mod:admin:menuClosed', true );
// $conf->createSetupValue( 'mod:admin:defaultURL', 'topic/10' );


//  Nombre de la carpeta que se utiliza para importar traducciones (carpeta situada en la raíz del proyecto)
// $conf->createSetupValue( 'mod:admin:importFolder', 'importTranslations' );


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
$conf->createSetupValue( 'mod:geozzy:resource:systemRTypes', [
  'rtypeUrl',
  'rtypePage',
  'rtypeFile',
]);


// USER PROFILE
// $conf->createSetupValue( 'mod:geozzyUser:profile', '' );
// $conf->createSetupValue( 'mod:geozzyUser:recoveryPasswordRedirect', '/asdf' );


//
// Reglas para collections
//
$conf->createSetupValue( 'mod:geozzy:resource:collectionTypeRules', [
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
// $conf->createSetupValue( 'collections:imageProfile', [
//   'default' => 'wmdpi4',
//   'multimediaThumbnail' => 'imgMultimediaGallery',
//   'multimediaLong' => 'imageMultimediaLarge'
// ] );


//
// Reglas para comentarios
//
$conf->createSetupValue( 'mod:geozzy:resource:commentRules',
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
$conf->createSetupValue( 'mod:geozzy:resource:urlAliasPatterns', [
  'default' => '/',
  'rtypeUrl' => [ 'default' => '/url/' ],
  'rtypeFile' => [ 'default' => '/file/' ],
]);


//
// Limitando el contenido del sitemap.xml
//
$conf->createSetupValue( 'mod:geozzy:sitemap:ignoreRTypes', [
  'rtypeUrl',
  'rtypeFile',
]);
$conf->createSetupValue( 'mod:geozzy:sitemap:regexUrlDeny', [
  '#/INTERNO[-_]#',
  '#^(/..)?/userverified/#',
  '#^(/..)?/userprofile#',
]);
// $conf->createSetupValue( 'mod:geozzy:sitemap:regexUrlAllow', [ '#/politica-#', '#cookies#', '#user#' ] );
// $conf->createSetupValue( 'mod:geozzy:sitemap:disable', true );



//
//  Filedata Mod: Perfiles de imagen propios del proyecto
//
include 'setup-500.project.filedataImageProfiles.php';



//
//  Media server - SIEMPRE AL FINAL!!!
//

// $conf->createSetupValue( 'publicConf:globalVars', [ 'ALGO' ] );

// $conf->createSetupValue( 'publicConf:setupFields', [ 'mod:geozzy:resource:algo' ] );

// $conf->createSetupValue( 'publicConf:vars:algo', 1234 );

// $conf->addSetupValue( 'mod:mediaserver:publicConf:smarty:setupFields', 'user:session' );

//
//  Media server - SIEMPRE AL FINAL!!!
//  setup-999.last.php repasa los minimos
//
