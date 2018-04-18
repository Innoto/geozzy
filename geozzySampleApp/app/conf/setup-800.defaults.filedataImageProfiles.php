<?php
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
 *   /cgmlImg/{filedataId}/none/{fileName}.{fileExt}
 */


// SOLO SE CREAN SI NO EXISTEN!!!
// Si se ha definido previamente no se altera.

$conf->createSetupValue( 'mod:filedata:profile:none', array( 'width' => 2000, 'height' => 2000, 'cut' => false, 'enlarge' => false ) );

// $IMAGE_PROFILES
$conf->createSetupValue( 'mod:filedata:profile:mdpi4', array( 'width' => 640, 'height' => 480, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:hdpi4', array( 'width' => 960, 'height' => 720, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:xhdpi4', array( 'width' => 1280, 'height' => 960, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:mdpi16', array( 'width' => 640, 'height' => 360, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:hdpi16', array( 'width' => 960, 'height' => 540, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:xhdpi16', array( 'width' => 1280, 'height' => 720, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );

// $IMAGE_PROFILES (web)
$conf->createSetupValue( 'mod:filedata:profile:wsdpi4', array( 'width' => 320, 'height' => 240, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:wmdpi4', array( 'width' => 640, 'height' => 480, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:whdpi4', array( 'width' => 960, 'height' => 720, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:wxhdpi4', array( 'width' => 1280, 'height' => 960, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:wsdpi16', array( 'width' => 320, 'height' => 180, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:wmdpi16', array( 'width' => 640, 'height' => 360, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:whdpi16', array( 'width' => 960, 'height' => 540, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:wxhdpi16', array( 'width' => 1280, 'height' => 720, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );

$conf->createSetupValue( 'mod:filedata:profile:modFormTn', array( 'width' => 240, 'height' => 180, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 60 ) );

$conf->createSetupValue( 'mod:filedata:profile:big', array( 'width' => 2000, 'height' => 2000, 'cut' => false, 'enlarge' => false, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:fast', array( 'width' => 400, 'height' => 300, 'cut' => false, 'rasterColor' => '#000000', 'backgroundColor' => '#FFFFFF', 'saveFormat' => 'JPEG', 'saveQuality' => 50 ) );
$conf->createSetupValue( 'mod:filedata:profile:fast_cut', array( 'width' => 400, 'height' => 300, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 50 ) );
$conf->createSetupValue( 'mod:filedata:profile:squareCut', array( 'width' => 128, 'height' => 128, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );

$conf->createSetupValue( 'mod:filedata:profile:resourceCut', array( 'width' => 270, 'height' => 153, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );
$conf->createSetupValue( 'mod:filedata:profile:resourceBigCut', array( 'width' => 340, 'height' => 193, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );

$conf->createSetupValue( 'mod:filedata:profile:imgMultimediaGallery', array( 'width' => 250, 'height' => 250, 'cut' => false, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ) );


$conf->createSetupValue( 'mod:filedata:profile:exp1', array( 'width' => 200, 'height' => 150 ) );
$conf->createSetupValue( 'mod:filedata:profile:rec1', array( 'width' => 400, 'height' => 300, 'saveName' => 'rec1.png', 'saveFormat' => 'PNG' ) );


/*-------------------------------- PERFILES DE POIS----------------------------------------*/
$conf->createSetupValue( 'mod:filedata:profile:resourcePoisCollection', array( 'width' => 20, 'height' => 20, 'cut' => false, 'rasterColor' => '#FFFFFF',
  'backgroundImg' => '/module/rextPoiCollection/img/chapaPOIS.png', 'padding' => 4, 'saveName' => 'marker.png', 'saveFormat' => 'PNG' ) );












/*-----------------------------------------TEMP ----------------------------------------------------------------------*/

// $conf->createSetupValue( 'mod:filedata:profile:resourceLg', array( 'width' => 2000, 'height' => 700, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );
// $conf->createSetupValue( 'mod:filedata:profile:resourceMd', array( 'width' => 1600, 'height' => 600, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );
// $conf->createSetupValue( 'mod:filedata:profile:resourceSm', array( 'width' => 1199, 'height' => 500, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );
// $conf->createSetupValue( 'mod:filedata:profile:resourceXs', array( 'width' => 991, 'height' => 450, 'saveFormat' => 'JPEG', 'saveQuality' => 75 ) );

// $conf->createSetupValue( 'mod:filedata:profile:ancho', array( 'width' => 400, 'height' => 200 ) );
// $conf->createSetupValue( 'mod:filedata:profile:alto', array( 'width' => 200, 'height' => 400 ) );

// $conf->createSetupValue( 'mod:filedata:profile:typeIcon', array(
//   'width' => 36, 'height' => 36,
//   'rasterColor' => '#ffffff', 'rasterResolution' => array( 'x'=>200, 'y'=>200 ),
//   'padding' => 5, 'saveName' => 'icon.png', 'saveFormat' => 'PNG' ) );

// $conf->createSetupValue( 'mod:filedata:profile:typeIconHover', array(
//   'width' => 36, 'height' => 36,
//   'backgroundImg' => '/app/classes/view/templates/img/chapas/chapaFilterHover36x36.png',
//   'rasterColor' => '#ffffff', 'rasterResolution' => array( 'x'=>200, 'y'=>200 ),
//   'padding' => 5, 'saveName' => 'iconHover.png', 'saveFormat' => 'PNG' ) );

// $conf->createSetupValue( 'mod:filedata:profile:typeIconSelected', array(
//   'width' => 36, 'height' => 36,
//   'backgroundImg' => '/app/classes/view/templates/img/chapas/chapaRest36x36.png',
//   'rasterColor' => '#ffffff', 'rasterResolution' => array( 'x'=>200, 'y'=>200 ),
//   'padding' => 5, 'saveName' => 'iconSelected.png', 'saveFormat' => 'PNG' ) );

// $conf->createSetupValue( 'mod:filedata:profile:resourceLg', array( 'width' => 2000, 'height' => 700) );
// $conf->createSetupValue( 'mod:filedata:profile:resourceMd', array( 'width' => 1200, 'height' => 500) );
// $conf->createSetupValue( 'mod:filedata:profile:resourceSm', array( 'width' => 768, 'height' => 300) );

/*
// TEST
$conf->createSetupValue( 'mod:filedata:profile:svgTest',
  array(
    'width' => 100, 'height' => 64, 'cut' => false, 'rasterColor' => '#00FF00',
    //'rasterResolution' => array( 'x'=>200, 'y'=>200 ),
    'padding' => array( 0, 18, 0, 18 ), 'backgroundColor' => '#F000F0','saveFormat' => 'PNG', 'cache' => false
  )
);

// CHAPA TEST
$conf->createSetupValue( 'mod:filedata:profile:chapaTest',
  array(
    'width' => 24, 'height' => 24, 'cut' => false, 'rasterColor' => '#FFFFFF', 'padding' => 5,
    //'padding' => array( 5, 5, 5, 5 ),
    'backgroundColor' => '#F000F0', 'backgroundImg' => '/chapa_paisaxes.png',
    //'backgroundImg' => '/module/MODULENAME/chapa_paisaxes.png',
    //'backgroundImg' => '/app/classes/view/templates/img/chapa_paisaxes.png',
    'saveFormat' => 'PNG', 'cache' => false
  )
);
*/



/*
identify -list format
   Format  Module    Mode  Description
-------------------------------------------------------------------------------
      BMP* BMP       rw-   Microsoft Windows bitmap image
     BMP2* BMP       -w-   Microsoft Windows bitmap image (V2)
     BMP3* BMP       -w-   Microsoft Windows bitmap image (V3)

      GIF* GIF       rw+   CompuServe graphics interchange format
    GIF87* GIF       rw-   CompuServe graphics interchange format (version 87a)

     JPEG* JPEG      rw-   Joint Photographic Experts Group JFIF format (80)
      JPG* JPEG      rw-   Joint Photographic Experts Group JFIF format (80)
    PJPEG* JPEG      rw-   Joint Photographic Experts Group JFIF format (80)

      PNG* PNG       rw-   Portable Network Graphics (libpng 1.2.50) http://www.libpng.org/ PNG format.
    PNG24* PNG       rw-   opaque 24-bit RGB (zlib 1.2.8)
    PNG32* PNG       rw-   opaque or transparent 32-bit RGBA
     PNG8* PNG       rw-   8-bit indexed with optional binary transparency
      JNG* PNG       rw-   JPEG Network Graphics
      MNG* PNG       rw+   Multiple-image Network Graphics (libpng 1.2.50)

      SVG  SVG       rw+   Scalable Vector Graphics (RSVG 2.40.1)
     SVGZ  SVG       rw+   Compressed Scalable Vector Graphics (RSVG 2.40.1)
     MSVG  SVG       rw+   ImageMagick's own SVG internal renderer

   GROUP4* TIFF      rw-   Raw CCITT Group4
     PTIF* TIFF      rw+   Pyramid encoded TIFF
     TIFF* TIFF      rw+   Tagged Image File Format (LIBTIFF, Version 4.0.3)
   TIFF64* TIFF      rw-   Tagged Image File Format (64-bit) (LIBTIFF, Version 4.0.3)
*/
