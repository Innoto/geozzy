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
cogumeloSetSetupValue( 'mail', array(
  'type' => 'smtp',
  'host' => 'smtp.gmail.com',
  'port' => '587',
  'auth' => true,
  'user' => 'info@plataformaproyecta.org',
  'pass' => '55Pr0y3ct4m0s',
  'secure' => 'tls',
  'fromName' => 'Info Plataforma Proyecta',
  'fromEmail' => 'info@plataformaproyecta.org'
));
// cogumeloSetSetupValue( 'mail', array(
//   'type' => 'smtp',#topic/19
//   'user' => 'sender@plataformaproyecta.org',
//   'pass' => 'APY2-f5.3R,Z',
//   'secure' => 'tls',
//   'fromName' => 'Plataforma Proyecta Sender',
//   'fromEmail' => 'sender@plataformaproyecta.org'
// ));


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
cogumeloSetSetupValue( 'lang', array(
  'available' => array(
    'es' => array(
      'i18n' => 'es_ES',
      'name' => 'castellano' ),
    'gl' => array(
      'i18n' => 'gl_ES',
      'name' => 'galego' )
  ),
  'default' => 'es'
));


//
// Redes sociles del proyecto
//
cogumeloSetSetupValue( 'socialNetworks', [
  'facebook' => 'plataformaproyecta',
  'twitter' => 'proyectaensino',
  'youtube' => 'plataformaproyecta',
  'googleplus' => 'PlataformaProyecta'
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
cogumeloSetSetupValue( 'mod:filedata:verifyAKeyUrl', true );



//
// Logo personalizado en Admin.
//
cogumeloSetSetupValue( 'mod:admin:logoPath', '/img/logoProyecta.png' );

////// Tribunal.
cogumeloSetSetupValue( 'mod:tribunal:educa:topic', 'ParticipaInnovacion' );

//
//  Module load
//
global $C_ENABLED_MODULES;
$C_ENABLED_MODULES = array(
  'cogumeloSession',
  'detectMobile',
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'common',
  'devel',
  'user',
  'filedata',
  'geozzy',
  'appResourceBridge',
  'admin',
  'tribunal',
  'form',
  'table',
  'explorer',
  'appExplorer'
);

// resource Extenssions
global $C_REXT_MODULES;
$C_REXT_MODULES = array(
  'rextMapDirections',
  'rextUrl',
  'rextView',
  'rextFile',
  'rextContact',
  'rextMap',
  'rextEvent',
  'rextSocialNetwork',
  'rextAppPageStatus',
  'rextAppInspiratics',
  'rextAppContest',
  'rextAppCampaign',
  'rextAppCollPast',
  'rextAppCollComponents',
  'rextAppBlog',
  'rextAppDINomina',
  'rextAppDIParticipa',
  'rextAppInnParticipa',
  'rextAppEduca',
  'rextProfilesSocialNetworks',
  'rextAppPeople',
  'rextAppProject',
);

// resource Types
global $C_RTYPE_MODULES;
$C_RTYPE_MODULES = array(
  'rtypePage',
  'rtypeUrl',
  'rtypeFile',
  'rtypeAppPageStatus',
  'rtypeAppInspiratics',
  'rtypeAppContest',
  'rtypeAppCampaign',
  'rtypeAppPeople',
  'rtypeAppProject',
  'rtypeAppWorkshop',
  'rtypeAppBlog',
  'rtypeAppDINomina',
  'rtypeAppDIParticipa',
  'rtypeAppInnParticipa',
  'rtypeAppEduca',
  'rtypeAppConcepto',
);

// resource Types
global $C_ULTIMATE_MODULES;
$C_ULTIMATE_MODULES = array(
  'geozzyAPI',
  'initResources',
  'geozzyUser'
);

// Merge all modules
$C_ENABLED_MODULES = array_merge( $C_ENABLED_MODULES, $C_REXT_MODULES, $C_RTYPE_MODULES, $C_ULTIMATE_MODULES );


// before app/Cogumelo.php execution
// Needed for modules with their own urls
global $C_INDEX_MODULES;
$C_INDEX_MODULES  = array(
  'cogumeloSession',
  'detectMobile',
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
  'explorer',
  'appExplorer',
  'geozzyAPI',
  'appResourceBridge',
  'rtypeAppDINomina',
  'rtypeAppDIParticipa',
  'rtypeAppInnParticipa',
  'initResources',
  'devel'
); // DEVEL SIEMPRE DE ULTIMO!!!


//
// RTypes de "uso interno"
//
cogumeloSetSetupValue( 'mod:geozzy:resource:systemRTypes',
  array(
    'rtypeUrl',
    'rtypePage',
    'rtypeFile'
  )
);

//USER PROFILE
cogumeloSetSetupValue( 'mod:geozzyUser', array(
  'profile' => ''
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
// Reglas para collections
//
cogumeloSetSetupValue( 'mod:geozzy:resource:collectionTypeRules',
  [
    'default' => [
      'default' => [
        'listOptions' =>  [ 'rtypeAppPageStatus', 'rtypeAppInspiratics', 'rtypeAppContest', 'rtypeAppCampaign',
          'rtypeAppWorkshop', 'rtypeAppPeople', 'rtypeAppProject', 'rtypeAppBlog', 'rtypeAppEduca' ],
        'manualCreate' => ['rtypeUrl'],
        'widget' => []
      ],
      'multimedia' => [
        'listOptions' =>  [],
        'manualCreate' => ['rtypeUrl', 'rtypeFile' ]
      ],
      'eventos' => [
        'listOptions' =>  [ 'rtypeEvent' ],
        'manualCreate' => []
      ],
      'appCollProject' => [
        'listOptions' =>  [ 'rtypeAppProject', 'rtypeAppInnParticipa'],
        'manualCreate' => []
      ],
      'appCollPast' => [
        'listOptions' =>  [ 'rtypeAppCampaign', 'rtypeAppInspiratics', 'rtypeAppContest'  ],
        'manualCreate' => []
      ],
      'appCollPeople' => [
        'listOptions' =>  [ 'rtypeAppPeople' ],
        'manualCreate' => []
      ],
      'appCollWorkshop' => [
        'listOptions' =>  [ 'rtypeAppWorkshop' ],
        'manualCreate' => []
      ]
    ],
    'rtypeAppBlog' => [
      'base' => [
        'listOptions' =>  [ 'rtypeAppInspiratics','rtypeAppContest','rtypeAppCampaign', 'rtypeAppBlog' ],
        'manualCreate' => ['rtypeUrl']
      ]
    ],
    'rtypeAppEduca' => [
      'appEducaEjemplos' => [
        'listOptions' =>  [ 'rtypeAppEduca', 'rtypeAppInspiratics', 'rtypeAppContest', 'rtypeAppBlog' ],
        'manualCreate' => [ 'rtypeUrl' ]
      ],
      'appEducaConceptos' => [
        'listOptions' =>  [ 'rtypeAppConcepto' ]
      ]
    ],
    'rtypeAppContest' => [
      'appCollFinalist' => [
        'listOptions' =>  [ 'rtypeAppInnParticipa' ]
      ]
    ]
  ]
);


//
// Alias por defecto en recursos
//
cogumeloSetSetupValue( 'mod:geozzy:resource:urlAliasPatterns',
  [
    'default' => '/',
    'rtypeAppContest' => [ 'default' => '/concurso/' ],
    'rtypeAppInspiratics' => [ 'default' => '/inspiratics/' ],
    'rtypeAppBlog' => [
      'default' => '/blog/',
      'gl' => '/blogue/'
    ],
    'rtypeAppEduca' => [ 'default' => '/recursos-educativos/' ],
    'rtypeAppPeople' => [ 'default' => '/colaboradores/' ],

    'rtypeAppProject' => [ 'default' => '/INTERNO_Project/' ],
    'rtypeAppWorkshop' => [ 'default' => '/INTERNO_Workshop/' ],
    'rtypeAppCampaign' => [ 'default' => '/INTERNO_Campaign/' ],
    'rtypeAppDINomina' => [ 'default' => '/INTERNO_DINomina/' ],
    'rtypeAppDIParticipa' => [ 'default' => '/INTERNO_DIParticipa/' ],
    'rtypeAppInnParticipa' => [ 'default' => '/INTERNO_InnParticipa/' ],
    'rtypeAppConcepto' => [ 'default' => '/INTERNO_Concepto/' ],

    'rtypeUrl' => [ 'default' => '/url/' ],
    'rtypeFile' => [ 'default' => '/file/' ],
  ]
);


//
// Limitando el contenido del sitemap.xml
//
cogumeloSetSetupValue( 'mod:geozzy:sitemap:ignoreRTypes', array(
  'rtypeUrl',
  'rtypeFile',
  // 'rtypeAppBlog',
  'rtypeAppCampaign',
  'rtypeAppPeople',
  'rtypeAppProject',
  'rtypeAppWorkshop',
  'rtypeAppDINomina',
  'rtypeAppDIParticipa',
  'rtypeAppInnParticipa',
  'rtypeAppConcepto',
));
// cogumeloSetSetupValue( 'mod:geozzy:sitemap:regexUrlAllow', [ '#/politica-#', '#cookies#', '#user#' ] );
cogumeloSetSetupValue( 'mod:geozzy:sitemap:regexUrlDeny', [ '#/INTERNO[-_]#', '#/userverified/#', '#/recursos-educativos#' ] );





//
//  Media server - SIEMPRE AL FINAL!!!
//
cogumeloSetSetupValue( 'publicConf:globalVars', array( 'C_LANG', 'C_SESSION_ID' ) );

cogumeloSetSetupValue( 'publicConf:setupFields', array( 'lang:available', 'lang:default', 'socialNetworks',
  'session:lifetime', 'google:maps:key', 'google:recaptcha:key:site', 'mod:geozzy:resource:directUrl', 'date:timezone' ) );

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
//  Media server - SIEMPRE AL FINAL!!!
//


