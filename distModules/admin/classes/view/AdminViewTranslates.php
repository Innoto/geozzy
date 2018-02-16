<?php
admin::load('view/AdminViewMaster.php');

class AdminViewTranslates extends AdminViewMaster {

  public $cacheQuery = false; // false, true or time in seconds

  public function __construct( $baseDir ) {
    parent::__construct($baseDir);

    $cache = Cogumelo::getSetupValue('cache:geozzyAPIView');
    if( $cache !== null ) {
      Cogumelo::log( __METHOD__.' ---- ESTABLECEMOS CACHE A '.$cache, 'cache' );
      $this->cacheQuery = $cache;
    }
  }

  // Control de acceso: solo superAdmin
  public function accessCheckTranslates() {
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions();
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }
  }



  /* EXPORT */
  public function resourcesExportView() {
    $this->exportView( 'resource' );
  }

  public function collectionsExportView() {
    $this->exportView( 'collections' );
  }

  public function exportView( $exportType ) {
    // Control de acceso: solo superAdmin
    $this->accessCheckTranslates();

    $template = new Template( $this->baseDir );
    $template->setTpl( 'translatesPage.tpl', 'admin' );
    $template->assign( 'exportType', $exportType );
    $template->assign( 'translateType', 'export' );

    if( $exportType === 'resource') {
      $rTypeModel = new ResourcetypeModel();
      $rTypeList = $rTypeModel->listItems( array(
        'filters' => array( 'notIdName' => [ 'rtypeUrl', 'rtypeFile', 'rtypeFavourites', 'rtypeTravelPlanner' ] )
      ) );

      $rTypeData['all'] = [ 'id' => '', 'name' => 'Todos' ];
      if( is_object( $rTypeList ) ) {
        while( $rTypeObj = $rTypeList->fetch() ) {
          $rTypeData[ $rTypeObj->getter('idName') ] = [
            'id' => $rTypeObj->getter('id'),
            'name' => $rTypeObj->getter('name')
          ];
        }
      }
      $template->assign( 'rTypeData', $rTypeData );
    }

    $this->template->assign( 'headTitle', __( 'Export' ) );
    $this->template->addToFragment( 'col12', $template );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  // Fichero para exportar: formato JSON
  public function resourcesExport() {
    // Control de acceso: solo superAdmin
    $this->accessCheckTranslates();

    $rTypeId = !empty( $_GET['rtype'] ) ? $_GET['rtype'] : false;
    $timeCreation = !empty( $_GET['creationDate'] ) ? $_GET['creationDate'] : false;
    $timeLastUpdate = !empty( $_GET['updateDate'] ) ? $_GET['updateDate'] : false;
    $langFromExport = !empty( $_GET['lang'] ) ? $_GET['lang'] : false;
    // $langFromExport = 'gl'; //  Poner idioma manualmente

    $langAvailableIds = Cogumelo::getSetupValue( 'publicConf:vars:langAvailableIds' );
    $langTrue = in_array( $langFromExport, $langAvailableIds ); //  Verificar si el idioma instroducido es correcto

    if( $langTrue ) {
      $filters = [];
      // Fecha creación mayor/igual a...
      if( $timeCreation ) {
        $filters['createdfrom'] = $timeCreation;
      }

      // Fecha actualización mayor/igual a...
      if( $timeLastUpdate ) {
        $filters['lastUpdatefrom'] = $timeLastUpdate;
      }

      // rtype específico o todos
      if( is_numeric( $rTypeId ) ) {
        $filters['rTypeId'] = $rTypeId;
      }
      else{
        // rTypes no permitidos (pilla todos los demás)
        $filters['notInRtypeIdName'] = [ 'rtypeUrl', 'rtypeFile', 'rtypeFavourites', 'rtypeTravelPlanner' ];
      }

      $resourceModel = new ResourceViewModel();
      $resourceList = $resourceModel->listItems( array(
        'filters' => $filters,
        'cache' => $this->cacheQuery
      ) );

      header( 'Content-disposition: attachment; filename=resources_'.$langFromExport.'.json' );
      header( 'Content-Type: application/json; charset=utf-8' );
      echo '[{"resources":[';
      $c = '';
      while( $valueobject = $resourceList->fetch() ) {
        $allData = [];

        $allCols = array( 'id', 'timeCreation', 'timeLastUpdate', 'title', 'shortDescription', 'mediumDescription', 'content' );
        foreach( $allCols as $col ) {
          if( $col === 'id' || $col === 'timeCreation' || $col === 'timeLastUpdate' ) {
            $allData[ $col ] = $valueobject->getter( $col );
          }
          elseif( !empty( $valueobject::$cols[$col]['multilang'] ) ) {
            $allData[ $col ] = $valueobject->getter( $col, $langFromExport );
          }
        }

        // Load all REXT related models
        $relatedModels = $valueobject->getRextModels();

        foreach( $relatedModels as $relModelIdName => $relModel ) {
          $rexData = array();
          if( is_object($relModel) && method_exists( $relModel, 'getAllData' ) ) {
            $allCols = $relModel->getCols( false );
            foreach( $allCols as $colName => $colInfo ) {
              if( $colName === 'id' ) {
                $rexData[ $colName ] = $relModel->getter( $colName );
              }
              elseif( !empty( $relModel::$cols[$colName]['multilang'] ) && $relModel::$cols[$colName]['type'] !== 'INT' && $colName !== 'textGplus' ) {
                $rexData[ $colName ] = $relModel->getter( $colName, $langFromExport );
              }
            }

            if( count( $rexData ) > 1 ) {
              $allData['rextmodels'][$relModelIdName] = $rexData;
            }
          }
        }

        echo $c.json_encode( $allData );
        $c=',';
      } // while
      echo ']}]';

    }
  }

  public function collectionsExport() {
    // Control de acceso: solo superAdmin
    $this->accessCheckTranslates();

    $timeCreation = !empty( $_GET['creationDate'] ) ? $_GET['creationDate'] : false;
    $timeLastUpdate = !empty( $_GET['updateDate'] ) ? $_GET['updateDate'] : false;
    $langFromExport = !empty( $_GET['lang'] ) ? $_GET['lang'] : false;
    // $langFromExport = 'gl'; //  Poner idioma manualmente

    $langAvailableIds = Cogumelo::getSetupValue( 'publicConf:vars:langAvailableIds' );
    $langTrue = in_array( $langFromExport, $langAvailableIds ); //  Verificar si el idioma instroducido es correcto

    if( $langTrue ) {
      $filters = [];
      // Fecha creación mayor/igual a...
      /*if( $timeCreation ) {
        $filters['createdfrom'] = $timeCreation; // poner esta condicion en el modelo de CollectionModel
      }*/

      // Fecha actualización mayor/igual a...
      /*if( $timeLastUpdate ) {
        $filters['lastUpdatefrom'] = $timeLastUpdate;
      }*/

      $resCollModel =  new CollectionModel();
      $collResList = $resCollModel->listItems( array( 'filters' => $filters, 'cache' => $this->cacheQuery ) );

      header( 'Content-disposition: attachment; filename=collections_'.$langFromExport.'.json' );
      header( 'Content-Type: application/json; charset=utf-8' );
      echo '[{"collections":[';
      $c = '';
      // Collections
      while( $valueobject = $collResList->fetch() ) {
        $allData = [];
        $allCols = array( 'id', 'timeCreation', 'timeLastUpdate', 'title', 'shortDescription', 'description' );
        foreach( $allCols as $col ) {
          if( $col === 'id' || $col === 'timeCreation' || $col === 'timeLastUpdate'  ) {
            $allData[ $col ] = $valueobject->getter( $col );
          }
          else {
            $allData[ $col ] = $valueobject->getter( $col, $langFromExport );
          }
        }

        echo $c.json_encode( $allData );
        $c=',';
      } // while
      echo ']}]';
    }
  }



  /* IMPORT */
  public function filesImportView() {
    // Control de acceso: solo superAdmin
    $this->accessCheckTranslates();

    $template = new Template( $this->baseDir );
    $template->setTpl( 'translatesPage.tpl', 'admin' );
    $template->assign( 'translateType', 'import' );

    $this->template->assign( 'headTitle', __( 'Import' ) );
    $this->template->addToFragment( 'col12', $template );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  public function filesImport() {

    // Nombre de la carpeta donde se ubican los ficheros para importar
    $nameImportFolder = Cogumelo::getSetupValue('mod:admin:importFolder');

    // Idioma para importar
    $langToImport = !empty( $_GET['lang'] ) ? $_GET['lang'] : false;
    // $langToImport = 'gl'; //  Poner idioma manualmente
    $langAvailableIds = Cogumelo::getSetupValue( 'publicConf:vars:langAvailableIds' );
    $langTrue = in_array( $langToImport, $langAvailableIds ); //  Verificar si el idioma instroducido es correcto

    $filesFolder = [];
    $directory = getcwd().'/../'.$nameImportFolder.'/'.$langToImport;

    if( $langTrue && is_dir( $directory ) ) {
      echo '<p>Idioma seleccionado para importar <strong>Código ISO: '.$langToImport.'</strong><p>';

      // Directorio donde queremos importar
      $filesDirectory = scandir( $directory );

      // Quitamos '.', '..'
      $filesFolder = array_diff( $filesDirectory, array( '..', '.' ) );
    }

    if( count($filesFolder) ) {

      foreach( $filesFolder as $fileName ) {

        $fileJson = file_get_contents( $directory.'/'.$fileName);
        if( $fileJson !== false ) {
          $fileDataArray = json_decode( $fileJson );

          // Arrays para actualizar/guardar datos del recurso base y modelos relacionados
          $updateDataRes = [];
          $updateDataModelRelatedRes = [];
          // Array para actualizar/guardar datos de las colecciones
          $updateDataCol = [];

          foreach( $fileDataArray as $file ) {
            foreach( (array) $file as $typeFile => $typeData ) {
              if( $typeFile === 'resources' ) {
                // JSON recursos
                foreach( (array) $typeData as $resData ) {
                  $updateDataRes[$resData->id] = [];
                  foreach( (array) $resData as $idField => $valueField ) {
                    if( !is_object( $valueField ) ) {
                      // Recurso base
                      if( $idField!=='timeCreation' && $idField!=='timeLastUpdate' ) {
                        $updateDataRes[$resData->id][$idField] = $valueField;
                      }
                    }
                    else{
                      // Modelos relacionados con el recurso base
                      foreach( (array) $valueField as $nameModel => $modelRelated ) {
                        if( !empty($nameModel) ) {
                          $updateDataModelRelatedRes[$nameModel][$modelRelated->id] = [];
                          foreach( (array) $modelRelated as $idFieldModelRelated => $valueFieldModelRelated ) {
                            $updateDataModelRelatedRes[$nameModel][$modelRelated->id][$idFieldModelRelated] = $valueFieldModelRelated;
                          }
                        }
                      }
                    }
                  }
                }
              }
              elseif( $typeFile === 'collections' ) {
                // JSON collections
                foreach( (array) $typeData as $colData ) {
                  $updateDataCol[$colData->id] = [];
                  foreach( (array) $colData as $idField => $valueField ) {
                    if( $idField!=='timeCreation' && $idField!=='timeLastUpdate' ) {
                      $updateDataCol[$colData->id][$idField] = $valueField;
                    }
                  }
                }
              }
            }
          }

          echo '<hr><p>- <em>Arquivo: '.$fileName.'</em></p>';
          // Actualizamos/guardamos las traducciones según idioma del recurso base
          if( !empty( $updateDataRes ) ) {
            echo '<div class="well"><p><ins>"Resources"</ins></p><p>INICIO: Actualizando... "Resources"</p>';
            foreach( $updateDataRes as $resData ) {
              $resModel = new ResourceModel();
              foreach( $resData as $idField => $valueField ) {
                if( $idField === 'id' ) {
                  // echo '<p>'.$idField.' Resource: '.$valueField.'</p>';
                  $resModel->setter( $idField, $valueField );
                }
                else {
                  // echo '<p>'.$idField.' Resource: '.$valueField.'</p>';
                  if( !empty( $resModel::$cols[$idField]['size'] ) ) {
                    $maxSizeField = (int) $resModel::$cols[$idField]['size'];
                    $currentSizeField = strlen( $valueField );
                    if( $maxSizeField < $currentSizeField ) {
                      $valueField = substr( $valueField, 0, $maxSizeField );
                      echo '<div class="alert alert-danger">';
                      echo '<p><strong>Id extensión '.$resData['id'].'</strong> do modelo ResourceModel:<p>';
                      echo '<p>O campo <strong>'.$idField.'</strong> ('.$currentSizeField.' caract.) superou o límite de caracteres establecidos (máx. '.$maxSizeField.') <em>Procediuse a recortar o texto para cumprir as regras establecidas</em></p>';
                      echo '</div>';
                    }
                  }
                  $resModel->setter( $idField, $valueField, $langToImport );
                }
              }
              $resModel->save();
            }
            echo '<p>FIN: Actualizando... "Resources"</p></div>';
          }

          // Actualizamos/guardamos las traducciones según idioma los modelos relacionados del recurso base
          if( !empty( $updateDataModelRelatedRes ) ) {
            echo '<div class="well"><p><ins>"modelRelated Resources"</ins></p><p>INICIO: Actualizando... "modelRelated Resources"</p>';
            foreach( $updateDataModelRelatedRes as $modelRelatedName => $modelRelatedData ) {
              echo '<p>- Modelo: <strong>'.$modelRelatedName.'</strong></p>';
              foreach( $modelRelatedData as $modelData ) {
                $resRelatedModel = new $modelRelatedName();
                foreach( $modelData as $idFieldModel => $valueFieldModel ) {
                  if( $idFieldModel === 'id' ) {
                    // echo '<p>'.$idFieldModel.' Ext: '.$valueFieldModel.'</p>';
                    $resRelatedModel->setter( $idFieldModel, $valueFieldModel );
                  }
                  else {
                    // echo '<p>'.$idFieldModel.' Ext: '.$valueFieldModel.'</p>';
                    if( !empty( $resRelatedModel::$cols[$idFieldModel]['size'] ) ) {
                      $maxSizeField = (int) $resRelatedModel::$cols[$idFieldModel]['size'];
                      $currentSizeField = strlen( $valueFieldModel );
                      if( $maxSizeField < $currentSizeField ) {
                        $valueFieldModel = substr( $valueFieldModel, 0, $maxSizeField );
                        echo '<div class="alert alert-danger">';
                        echo '<p><strong>Id extensión '.$modelData['id'].'</strong> do modelo '.$modelRelatedName.':<p>';
                        echo '<p>O campo <strong>'.$idFieldModel.'</strong> ('.$currentSizeField.' caract.) superou o límite de caracteres establecidos (máx. '.$maxSizeField.') <em>Procediuse a recortar o texto para cumprir as regras establecidas</em></p>';
                        echo '</div>';
                      }
                    }
                    $resRelatedModel->setter( $idFieldModel, $valueFieldModel, $langToImport );
                  }
                }
                $resRelatedModel->save();
              }
            }
            echo '<p>FIN: Actualizando... "modelRelated Resources"</p></div>';
          }

          // Actualizamos/guardamos las traducciones según idioma de las colecciones
          if( !empty( $updateDataCol ) ) {
            echo '<div class="well"><p><ins>"Collections"</ins></p><p>INICIO: Actualizando... "Collections"</p>';
            foreach( $updateDataCol as $colData ) {
              $colModel = new CollectionModel();
              foreach( $colData as $idField => $valueField ) {
                if( $idField === 'id' ) {
                  // echo '<p>'.$idField.' Collection: '.$valueField.'</p>';
                  $colModel->setter( $idField, $valueField );
                }
                else {
                  // echo '<p>'.$idField.' Collection: '.$valueField.'</p>';
                  if( !empty( $colModel::$cols[$idField]['size'] ) ) {
                    $maxSizeField = (int) $colModel::$cols[$idField]['size'];
                    $currentSizeField = strlen( $valueField );
                    if( $maxSizeField < $currentSizeField ) {
                      $valueField = substr( $valueField, 0, $maxSizeField );
                      echo '<div class="alert alert-danger">';
                      echo '<p><strong>Id extensión '.$colData['id'].'</strong> do modelo CollectionModel:<p>';
                      echo '<p>O campo <strong>'.$idField.'</strong> ('.$currentSizeField.' caract.) superou o límite de caracteres establecidos (máx. '.$maxSizeField.') <em>Procediuse a recortar o texto para cumprir as regras establecidas</em></p>';
                      echo '</div>';
                    }
                  }
                  $colModel->setter( $idField, $valueField, $langToImport );
                }
              }
              $colModel->save();
            }
            echo '<p>FIN: Actualizando... "Collections"</p></div>';
          }
        }
        else {
          echo '<p>Error de lectura. <em>Arquivo: '.$fileName.'</em></p>';
        }
      }// foreach($filesFolder)
      echo '<hr><p class="alert alert-success"><strong>IMPORT FINALIZADO</strong></p>';

    }
    else {
      if( empty( $nameImportFolder ) || !is_dir( $directory ) ) {
        echo '<p class="alert alert-warning">Non existe a carpeta ou non está definido o nome no arquivo de configuración</p>';
      }

      if( !count($filesFolder) && is_dir( $directory ) ) {
        echo '<p class="alert alert-warning">A carpeta non contén ningún arquivo</p>';
      }

      if( !$langTrue ) {
        echo '<p class="alert alert-warning">Non se seleccionou un idioma ou produciuse un erro no envío</p>';
      }
    }

  }
}