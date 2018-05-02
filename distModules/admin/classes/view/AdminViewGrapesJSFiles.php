<?php
admin::load('view/AdminViewMaster.php');
geozzy::load('view/GeozzyResourceView.php');



class AdminViewGrapesJSFiles extends AdminViewMaster {


  public function __construct() {

    $this->baseURL = '/cgmlformpublic';
    $this->filePathPublic = Cogumelo::getSetupValue('mod:filedata:filePathPublic');
    if( $this->filePathPublic ) {
      $this->filePathPublic = rtrim( $this->filePathPublic, ' /' );
    }

    parent::__construct();
  }

  public function fileList() {
    cogumelo::debug(__METHOD__);

    $result = [];

    if( !empty( $this->filePathPublic ) && is_dir( $this->filePathPublic ) ) {
      $dirElements = scandir( $this->filePathPublic );
      if( !empty( $dirElements ) ) {
        foreach( $dirElements as $object ) {
          if( $object !== '.' && $object !== '..' && !is_dir( $this->filePathPublic.'/'.$object ) ) {
            $result[] = $this->baseURL .'/'. $object;
          }
        }
      }
    }

    header('Content-type: application/json');
    echo json_encode( $result );
  } // function fileList() {


  public function fileUpload() {
    cogumelo::debug(__METHOD__);

    $result = false;

    $files = $this->makeUpload();

    $result = [
      'data' => $files
    ];

    header('Content-type: application/json');
    echo json_encode( $result );
  }


  private function makeUpload() {
    cogumelo::debug(__METHOD__);

    $result = [];


    if( !empty( $this->filePathPublic ) && !is_dir( $this->filePathPublic ) ) {
      @mkdir( $this->filePathPublic, 0770 );
    }


    if( !empty( $_FILES['grapesJSFilesUpload']['name'][0] ) ) {

      $numFiles = count( $_FILES['grapesJSFilesUpload']['name'] );

      for( $p=0; $p < $numFiles; $p++ ) { 
        $error = false;
        $fileData = [
          'name'     => $_FILES['grapesJSFilesUpload']['name'][ $p ],
          'type'     => $_FILES['grapesJSFilesUpload']['type'][ $p ],
          'tmp_name' => $_FILES['grapesJSFilesUpload']['tmp_name'][ $p ],
          'error'    => $_FILES['grapesJSFilesUpload']['error'][ $p ],
          'size'     => $_FILES['grapesJSFilesUpload']['size'][ $p ],
        ];

        if( $fileData['error'] !== UPLOAD_ERR_OK ) {
          $error = 'Fallo con '.$fileData['name'].' segun PHP ('.$fileData['error'].')';
          cogumelo::error(__METHOD__.': '.$error);
        }
        // Datos enviados fuera de rango
        if( !$error && $fileData['size'] < 1 ) {
          $error = 'Fallo con '.$fileData['name'].' por tamaño 0';
          cogumelo::error(__METHOD__.': '.$error);
        }
        // Verificando la existencia y tamaño del fichero intermedio
        if( !$error && 
          ( !is_uploaded_file( $fileData['tmp_name'] ) || 
          filesize( $fileData['tmp_name'] ) !== $fileData['size'] ) )
        {
          $error = 'Fallo con '.$fileData['name'].' polo tamaño';
          cogumelo::error(__METHOD__.': '.$error);
        }

        // Verificando el MIME_TYPE del fichero intermedio
        if( !$error ) {
          $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
          $fileTypePhp = finfo_file( $finfo, $fileData['tmp_name'] );
          if( $fileTypePhp !== false ) {
            if( $fileData['type'] !== $fileTypePhp ) {
              cogumelo::debug(__METHOD__.': ALERTA: Los MIME_TYPE reportados por el navegador y PHP difieren: '.
                $fileData['type'].' != '.$fileTypePhp );
              cogumelo::debug(__METHOD__.': ALERTA: Damos preferencia a PHP. Puede variar la validación JS/PHP' );
              $fileData['type'] = $fileTypePhp;
            }
          }
          else {
            cogumelo::debug(__METHOD__.': ALERTA: Imposible obtener el MIME_TYPE del fichero. Nos fiamos del navegador: '.$fileData['type'] );
          }

          // VALIDAR $fileData['type'] IMAGE
          if( strpos( $fileData['type'], 'image' ) !== 0 ) {
            $error = 'Fallo con '.$fileData['name'].' polo tipo';
            cogumelo::error(__METHOD__.': '.$error);
          }
        }

        // Una vez verificadas todas las condiciones
        if( !$error ) {

          form::load('controller/FormController.php');
          $form = new FormController();

          $secureName = $form->secureFileName( $fileData['name'] );
          // $secureName = $fileData['name'];

          $localeFile = $this->filePathPublic.'/'.$secureName;





          /**
           * TODO: FALTA VER QUE NON SE PISE UN ANTERIOR!!!
           */
          if( file_exists( $localeFile ) ) {
            $c=1;
            $sn = pathinfo( $secureName );
            $snName = $sn['filename'];
            $snExt = $sn['extension'];

            while( file_exists( $this->filePathPublic.'/'.$snName.'-c'.$c.'.'.$snExt ) ) {
              $c++;
            }
            $secureName = $snName.'-c'.$c.'.'.$snExt;
            $localeFile = $this->filePathPublic.'/'.$secureName;
          }





          if( !move_uploaded_file( $fileData['tmp_name'], $localeFile ) ) {
            $error = 'Fallo de move_uploaded_file pasando ('.$fileData['tmp_name'].') a ('.$localeFile.')';
            cogumelo::error(__METHOD__.': '.$error);
            $localeFile = false;
          }
          else {
            $result[] = $this->baseURL .'/'. $secureName;
          }


        }
        else {
          // unlink( $fileData['tmp_name'] );
        }
      }
    }


    return $result;
  } // function makeUpload() {



}
