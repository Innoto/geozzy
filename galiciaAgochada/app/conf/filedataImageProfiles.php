<?php
/*
 * Perfiles que se pueden aplicar a imágenes cargadas en filedata
 *
 *
 * Formatos de PERFIL
 *
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
 *
 * Formatos de URL
 *
 * Para cargar la imagen según un perfil:
 *
 *   /cgmlImg/{filedataId}/{profileName}/{fileName}.{fileExt}
 *   /cgmlImg/{filedataId}/{profileName}/{filedataId}.{fileExt}
 *   /cgmlImg/{filedataId}/{profileName}[/.*] (realiza un redirect al caso 1)
 *
 * Para cargar la imagen original sin procesar:
 *
 *   /cgmlImg/{filedataId}/{fileName}.{fileExt}
 *   /cgmlImg/{filedataId}/{filedataId}.{fileExt}
 *   /cgmlImg/{filedataId}[/.*] (realiza un redirect al caso 1)
 */

global $IMAGE_PROFILES;

$IMAGE_PROFILES = array(
  'mdpi4' => array( 'width' => 640, 'height' => 480, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ),
  'hdpi4' => array( 'width' => 960, 'height' => 720, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ),
  'xhdpi4' => array( 'width' => 1280, 'height' => 960, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ),
  'mdpi16' => array( 'width' => 640, 'height' => 360, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ),
  'hdpi16' => array( 'width' => 960, 'height' => 540, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ),
  'xhdpi16' => array( 'width' => 1280, 'height' => 720, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ),

  'big' => array( 'width' => 2000, 'height' => 2000, 'cut' => false, 'enlarge' => false, 'saveFormat' => 'JPEG', 'saveQuality' => 95 ),
  'fast' => array( 'width' => 400, 'height' => 300, 'cut' => false, 'saveFormat' => 'JPEG', 'saveQuality' => 50 ),
  'fast_cut' => array( 'width' => 400, 'height' => 300, 'cut' => true, 'saveFormat' => 'JPEG', 'saveQuality' => 50 ),
  'square_cut' => array( 'width' => 128, 'height' => 128, 'cut' => true ),
  'exp1' => array( 'width' => 200, 'height' => 150 ),
  'rec1' => array( 'width' => 400, 'height' => 300, 'saveName' => 'rec1.png', 'saveFormat' => 'PNG' ),
  'typeIcon' => array(
    'width' => 64, 'height' => 64,
    'backgroundColor' => '#00000000', 'rasterColor' => '#FFFFFF',
    'saveFormat' => 'PNG' ),
  'explorerMarker' => array(
    'width' => 32, 'height' => 32, 'cut' => false,
    'backgroundColor' => '#00000000', 'rasterColor' => '#00FF00',
    'saveName' => 'marker.png', 'saveFormat' => 'PNG' ),

  'resourceLg' => array( 'width' => 1200, 'height' => 500),
  'resourceMd' => array( 'width' => 992, 'height' => 400),
  'resourceSm' => array( 'width' => 768, 'height' => 300),



  // TEST
  'svgTest' => array(
    'width' => 320, 'height' => 240,
    'cut' => false,
    'backgroundColor' => '#00000040',
    //'rasterResolution' => array( 'x'=>200, 'y'=>200 ),
    'rasterColor' => '#FFFFFF',
    'saveFormat' => 'PNG', 'cache' => false
  ),



  'ancho' => array( 'width' => 400, 'height' => 200 ),
  'alto' => array( 'width' => 200, 'height' => 400 )
);



/* identify -list format
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
