<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyResourceView.php' );



class AdminViewElfinder extends AdminViewMaster {

  public function fileManagerBackend() {

    $filePathPublic = Cogumelo::getSetupValue('mod:filedata:filePathPublic');
    $baseURL = '/cgmlformpublic';

    if( !empty( $filePathPublic ) && !is_dir( $filePathPublic ) ) {
      @mkdir( $filePathPublic, 0770 );
    }



    // Documentation for connector options:
    // https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
    $opts = array(
      // 'debug' => true,
      'roots' => array(
        array(
          'driver'        => 'LocalFileSystem',  // driver for accessing file system (REQUIRED)
          'path'          => $filePathPublic,  // path to files (REQUIRED)
          'tmbURL'        => $baseURL.'/.tmb',
          'URL'           => $baseURL, // URL to files (REQUIRED)

          'hidden' => true,

          'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
          'uploadAllow'   => array('image'),// Mimetype `image` and `text/plain` allowed to upload
          'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
          'accessControl' => 'access',                     // disable and hide dot starting files (OPTIONAL)
          'attributes' => array(
            array(
              'pattern' => '#.tmb$#',
              'hidden' => true
            )
          )

        )
      )
    );

    // run elFinder
    $connector = new elFinderConnector(new elFinder($opts));
    $connector->run();
  }




  public function fileManagerFrontend() {
    ?>
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <title>elFinder 2.1.x source version with PHP connector</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />

        <!-- Section CSS -->
        <!-- jQuery UI (REQUIRED) -->
        <link rel="stylesheet" type="text/css" href="/vendor/yarn/jquery-ui-dist/jquery-ui.theme.css">

        <!-- elFinder CSS (REQUIRED) -->
        <link rel="stylesheet" type="text/css" href="/vendor/composer/studio-42/elfinder/css/elfinder.min.css">
        <link rel="stylesheet" type="text/css" href="/vendor/composer/studio-42/elfinder/css/theme.css">
        <script src="/vendor/yarn/jquery/dist/jquery.min.js"></script>
        <script src="/vendor/yarn/jquery-ui-dist/jquery-ui.min.js"></script>

        <!-- elFinder JS (REQUIRED) -->
        <script src="/vendor/composer/studio-42/elfinder/js/elfinder.min.js"></script>
        <!-- elFinder translation (OPTIONAL) -->
        <!--<script src="js/i18n/elfinder.es.js"></script>-->

        <!-- elFinder initialization (REQUIRED) -->
        <script type="text/javascript" charset="utf-8">
          // Documentation for client options:
          // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
          $(document).ready(function() {
            $('#elfinder').elfinder({
              url : '/admin/filemanagerbackend',  // connector URL (REQUIRED)
              // , lang: 'ru'                    // language (OPTIONAL)
              uiOptions: {
                toolbar : [
                  ['upload'],
                  ['open'],
                  ['rm']
                ]
              },

              contextmenu : {
                  files  : [
                      'getfile', '|','open', '|', 'copy', 'cut', 'paste', '|', 'rm'
                  ]
              }
            });
          });
        </script>
        <style>
        .ui-corner-bottom.elfinder-statusbar, .elfinder-navbar {
              display: none !important;
          }

        </style>
      </head>
      <body>

        <!-- Element where elFinder will be created (REQUIRED) -->
        <div id="elfinder"></div>

      </body>
    </html>

    <?php
  }

}
