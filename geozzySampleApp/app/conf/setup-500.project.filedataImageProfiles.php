<?php


$conf->setSetupValue( 'mod:filedata:profile:exp1', array(
  'width' => 200, 'height' => 150 ) );


$conf->setSetupValue( 'mod:filedata:profile:rec1', array(
  'width' => 400, 'height' => 300, 'saveName' => 'rec1.png', 'saveFormat' => 'PNG' ) );


$conf->setSetupValue( 'mod:filedata:profile:imagenBlog', array(
  'width' => 640, 'height' => 480, 'cut' => false, 'enlarge' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );


$conf->setSetupValue( 'mod:filedata:profile:uniteGallerySmall', array(
  'width' => 2000, 'height' => 400 , 'cut' => false, 'enlarge' => false, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );






/*
 * Perfiles que se pueden aplicar a imágenes cargadas en filedata
 *
 * Formatos de PERFIL
 *   'profileName' => array(
 *     'width' => {pxWidth},
 *     'height' => {pxWeight}
 *   )
 *   Parámetros opcionales:
 *     'cut' - default: true
 *     'enlarge' - default: true
 *     'saveFormat' - ['JPEG','PNG'] default: Original format
 *     'saveName' - default: Filedata 'name'
 *     'saveQuality'
 *
 * Formatos de URL
 *
 * Para cargar la imagen según un perfil:
 *   /cgmlImg/{filedataId}/{profileName}/{fileName}.{fileExt}
 *
 * Para cargar la imagen original sin procesar:
 *   /cgmlImg/{filedataId}/{fileName}.{fileExt}
 */