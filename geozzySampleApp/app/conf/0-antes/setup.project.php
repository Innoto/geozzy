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
//  Mail sender
//
/*
cogumeloSetSetupValue( 'mail', [
  'type' => 'smtp',
  'host' => '',
  'port' => '587',
  'auth' => true,
  'user' => '',
  'pass' => '',
  'secure' => 'tls',
  'fromName' => '',
  'fromEmail' => ''
]);
*/
/*
cogumeloSetSetupValue( 'mail', array(
  'type' => 'smtp',
  'host' => 'smtp.gmail.com',
  'port' => '587',
  'auth' => true,
  'user' => 'info@plataformaproyecta.org',
  'pass' => 'contraseña',
  'secure' => 'tls',
  'fromName' => 'Info Plataforma Proyecta',
  'fromEmail' => 'info@plataformaproyecta.org'
));
*/
// cogumeloSetSetupValue( 'mail', array(
//   'type' => 'smtp',#topic/19
//   'user' => 'sender@plataformaproyecta.org',
//   'pass' => 'contraseña',
//   'secure' => 'tls',
//   'fromName' => 'Plataforma Proyecta Sender',
//   'fromEmail' => 'sender@plataformaproyecta.org'
// ));

cogumeloSetSetupValue( 'app:ContactMail:receiver', 'czas@innoto.es' );
//
// Google keys
//
cogumeloSetSetupValue( 'google:maps:key', 'AIzaSyDkNx9IA9093KtrCRMVwUfgRi7vp_A_npQ' );
// cogumeloSetSetupValue( 'google:recaptcha:key:site', '6LcEeQwTAAAAABvylWDu4npaLmHoavDXX-PTJAJT' );
// cogumeloSetSetupValue( 'google:recaptcha:key:secret', '6LcEeQwTAAAAAKGvHVRz1Nug5jTfHTP9Ub2yUK-d' );
//
// DATOS PARA PROBAS GEOZZY
cogumeloSetSetupValue( 'google:recaptcha:key:site', '6LdUCwwUAAAAAOb026oMl2CS9_OT1pHbxmT6eqS6' );
cogumeloSetSetupValue( 'google:recaptcha:key:secret', '6LdUCwwUAAAAAFUEkes0vOPahU9vfRztGrRqj3Q-' );

//
// Lang
//
cogumeloSetSetupValue( 'lang', [
  'available' => [
    'es' => [
      'i18n' => 'es_ES',
      'name' => 'castellano'
    ]
  ],
  'default' => 'es'
]);


//
// URL alias controller: Fichero que contiene una clase UrlAliasController con un metodo getAlternative
//
cogumeloSetSetupValue( 'urlAliasController:classFile',
  COGUMELO_DIST_LOCATION.'/distModules/geozzy/classes/controller/UrlAliasController.php' );

//
// Error404 en una URL.
//   View = View que se muestra como aviso del error
//
cogumeloSetSetupValue( 'urlError403:view', 'PageErrorView::page403' );
cogumeloSetSetupValue( 'urlError404:view', 'PageErrorView::page404' );





//
// No se adminten URLs a ficheros solo con su Id
//
// cogumeloSetSetupValue( 'mod:filedata:verifyAKeyUrl', true );

//
// Logo personalizado en Admin.
//
cogumeloSetSetupValue( 'mod:admin:logoPath', '/img/logo.png' );
cogumeloSetSetupValue( 'mod:admin:titlePath', 'Profesores Admin' );
cogumeloSetSetupValue( 'mod:admin:defaultURL', 'topic/10' );
cogumeloSetSetupValue( 'mod:admin:menuClosed', true );

//
// Tribunal.
//
cogumeloSetSetupValue( 'mod:tribunal:logoPath', '/img/logo_tribunal.png' );
cogumeloSetSetupValue( 'mod:tribunal:profesores:topic', 'ProfesoresInscripcion' );



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
  'develWebPanel',
  'user',
  'filedata',
  'geozzy',
  'appResourceBridge',
  'admin',
  'tribunal',
  'form',
  'table',
  'crearEntrevistas'
];

// resource Extenssions
global $C_REXT_MODULES;
$C_REXT_MODULES = [
  'rextMapDirections',
  'rextUrl',
  'rextView',
  'rextFile',
  'rextMap',
  'rextAppProfesor',
  'rextAppConvocatoria',
  'rextAppEntrevista'
];

// resource Types
global $C_RTYPE_MODULES;
$C_RTYPE_MODULES = [
  'rtypePage',
  'rtypeUrl',
  'rtypeFile',
  'rtypeAppProfesor',
  'rtypeAppConvocatoria',
  'rtypeAppEntrevista'
];

// resource Types
global $C_ULTIMATE_MODULES;
$C_ULTIMATE_MODULES = [
  'privateAPI',
  'geozzyAPI',
  'initResources',
  'geozzyUser'
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
  'tribunal',
  'geozzyAPI',
  'appResourceBridge',
  'rtypeAppProfesor',
  'initResources',
  'privateAPI',
  'crearEntrevistas',
  'devel'

]; // DEVEL SIEMPRE DE ULTIMO!!!


//
// RTypes de "uso interno"
//
cogumeloSetSetupValue( 'mod:geozzy:resource:systemRTypes', [
  'rtypeUrl',
  'rtypePage',
  'rtypeFile'
]);

//GeozzyUser
cogumeloSetSetupValue( 'mod:geozzyUser:blockAccessRegister', false);
cogumeloSetSetupValue( 'mod:geozzyUser:blockVerifyMail', true);
cogumeloSetSetupValue( 'mod:geozzyUser:profile', '');

//
// Dependences PATH
//
cogumeloSetSetupValue( 'dependences', [
  'composerPath' => cogumeloGetSetupValue( 'setup:webBasePath' ).'/vendor/composer',
  'bowerPath' => cogumeloGetSetupValue( 'setup:webBasePath' ).'/vendor/bower',
  'manualPath' => cogumeloGetSetupValue( 'setup:webBasePath' ).'/vendor/manual',
  'manualRepositoryPath' => COGUMELO_LOCATION.'/packages/vendorPackages'
]);


//
//  Devel Mod
//
cogumeloSetSetupValue( 'mod:devel', [
  'allowAccess' => true,
  'url' => 'devel',
  'password' => 'd3v3lProfes239t6j7'
]);


//
//  i18n
//
cogumeloSetSetupValue( 'i18n', [
  'path' => cogumeloGetSetupValue( 'setup:appBasePath' ).'/conf/i18n',
  'localePath' => cogumeloGetSetupValue( 'setup:appBasePath' ).'/conf/i18n/locale',
  'gettextUpdate' => true // update gettext files when working in localhost
]);


//
// Reglas para collections
//
cogumeloSetSetupValue( 'mod:geozzy:resource:collectionTypeRules', [
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

cogumeloSetSetupValue( 'mod:geozzy:resource:commentRules',
  array(
    'default' => array(
      'moderation' => 'all', // none|verified|all
      'anonymous' => false,
      'ctype' => array() // 'comment','suggest'
    )
  )
);


//
// Alias por defecto en recursos
//
cogumeloSetSetupValue( 'mod:geozzy:resource:urlAliasPatterns', [
  'default' => '/',
  'rtypeUrl' => [ 'default' => '/url/' ],
  'rtypeFile' => [ 'default' => '/file/' ],
  'rtypeAppProfesor' => [ 'default' => '/INTERNO_Profesor/' ],
  'rtypeAppConvocatoria' => [ 'default' => '/INTERNO_Convocatoria/' ],
  'rtypeAppEntrevista' => [ 'default' => '/INTERNO_Entrevista/' ]
]);


//
// Limitando el contenido del sitemap.xml
//
cogumeloSetSetupValue( 'mod:geozzy:sitemap:ignoreRTypes', [
  'rtypeUrl',
  'rtypeFile',
  'rtypeAppProfesor',
  'rtypeAppConvocatoria',
  'rtypeAppEntrevista'
]);



//
//  Media server - SIEMPRE AL FINAL!!!
//
cogumeloSetSetupValue( 'publicConf:globalVars', [ 'C_LANG', 'C_SESSION_ID' ] );

cogumeloSetSetupValue( 'publicConf:setupFields', [ 'google:maps:key', 'google:recaptcha:key:site',
  'session:lifetime', 'lang:available', 'lang:default', 'mod:geozzy:resource:directUrl', 'date:timezone', 'mod:admin:defaultURL' ] );

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
  array_merge( cogumeloGetSetupValue( 'publicConf:setupFields' ), ['user:session'] )
);
//
//  Media server - SIEMPRE AL FINAL!!!
//
