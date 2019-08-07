<?php
geozzy::load( 'controller/RExtController.php' );

class RExtPanoramaController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextPanorama(), 'rExtPanorama_' );
  }

  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtPanoramaModel();
    $rExtList = $rExtModel->listItems( [ 'filters' => [ 'resource' => $resId ], 'cache' => $this->cacheQuery ] );
    $rExtObj = ( gettype( $rExtList ) === 'object' ) ? $rExtList->fetch() : false;

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

      // Añadimos los campos en el idioma actual o el idioma principal
      $resourceExtFields = $rExtObj->getCols();
      $langDefault = Cogumelo::getSetupValue('publicConf:vars:langDefault');
      foreach( $resourceExtFields as $key => $value ) {
        if( !isset( $rExtData[ $key ] ) ) {
          $rExtData[ $key ] = $rExtObj->getter( $key );
          // Si en el idioma actual es una cadena vacia, buscamos el contenido en el idioma principal
          if( $rExtData[ $key ] === '' && isset( $rExtData[ $key.'_'.$langDefault ] ) ) {
            $rExtData[ $key ] = $rExtData[ $key.'_'.$langDefault ];
          }
        }
      }

      // Cargo los datos tipo File
      $fileColumns = [ 'panoramicImage' ];
      foreach( $fileColumns as $fileColumn ) {
        if( isset( $rExtData[ $fileColumn ] ) ) {
          $fileData = $this->defResCtrl->getFiledata( $rExtData[ $fileColumn ] );
          if( $fileData ) {
            $rExtData[ $fileColumn ] = $fileData;
          }
        }
      }
    }

    // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
    // $termsGroupedIdName = $this->defResCtrl->getTermsInfoByGroupIdName( $resId );
    // if( $termsGroupedIdName !== false ) {
    //   foreach( $this->taxonomies as $tax ) {
    //     if( isset( $termsGroupedIdName[ $tax[ 'idName' ] ] ) ) {
    //       $rExtData[ $tax['idName'] ] = $termsGroupedIdName[ $tax[ 'idName' ] ];
    //     }
    //   }
    // }

    return ( count($rExtData) > 0 ) ? $rExtData : false;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {

    $rExtFieldNames = [];

    $fieldsInfo = [
      'horizontalAngleView' => [
        'params' => [
          'label' => __('Horizontal angle of view').' '.__('(in sexagesimal)'), Initial
          'value' => 360
        ],
        'rules' => [ 'digits' => true, 'min' => 0, 'max' => 360 ]
      ],
      'verticalAngleView' => [
        'params' => [
          'label' => __('Vertical angle of view').' '.__('(in sexagesimal)'), Initial
          'value' => 180
        ],
        'rules' => [ 'digits' => true, 'min' => 0, 'max' => 360 ]
      ],
      'panoramicImage' => [
        'params' => [
          'label' => __('Related panoramic image'), 'type' => 'file',
          'id' => 'rExtPanoramaPanoramicImage', 'destDir' => RExtPanoramaModel::$cols['panoramicImage']['uploadDir']
        ],
        'rules' => [ /*'fileRequired' => true,*/ 'accept' => 'image/jpeg,image/pjpeg'/*, 'maxfilesize' => '6300000'*/ ]
      ]
    ];

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Si es una edicion, añadimos el ID y cargamos los datos
    $valuesArray = $this->getRExtData( $form->getFieldValue( 'id' ) );
    if( $valuesArray ) {
      $valuesArray = $this->prefixArrayKeys( $valuesArray );
      $form->setField( $this->addPrefix( 'id' ), [ 'type' => 'reserved', 'value' => null ] );

      // Limpiando la informacion de terms para el form
      if( $this->taxonomies ) {
        foreach( $this->taxonomies as $tax ) {
          $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
          if( isset( $valuesArray[ $taxFieldName ] ) && is_array( $valuesArray[ $taxFieldName ] ) ) {
            $taxFieldValues = [];
            foreach( $valuesArray[ $taxFieldName ] as $value ) {
              $taxFieldValues[] = ( is_array( $value ) ) ? $value[ 'id' ] : $value;
            }
            $valuesArray[ $taxFieldName ] = $taxFieldValues;
          }
        }
      }

      $form->loadArrayValues( $valuesArray );
    }

    // Add RExt info to form
    foreach( $fieldsInfo as $fieldName => $info ) {
      if( isset( $info[ 'translate' ] ) && $info[ 'translate' ] ) {
        $rExtFieldNames = array_merge( $rExtFieldNames, $form->multilangFieldNames( $fieldName ) );
      }
      else {
        $rExtFieldNames[] = $fieldName;
      }
    }

    /*******************************************************************
     * Importante: Guardar la lista de campos del RExt en 'FieldNames' *
     *******************************************************************/
    //$rExtFieldNames[] = 'FieldNames';
    $form->setField( $this->addPrefix( 'FieldNames' ), [ 'type' => 'reserved', 'value' => $rExtFieldNames ] );

    $form->saveToSession();

    return( $rExtFieldNames );
  }


  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
   public function getFormBlockInfo( FormController $form ) {
     $formBlockInfo = parent::getFormBlockInfo( $form );

     $formBlockInfo['template']['admin'] = new Template();
     $formBlockInfo['template']['admin']->assign( 'rExtName', $this->rExtName );
     $formBlockInfo['template']['admin']->assign( 'rExt', $formBlockInfo );

     $formBlockInfo['template']['admin']->setTpl( 'rExtFormBlock.tpl', 'rextPanorama' );

     return $formBlockInfo;
   }


  /**
   * Validaciones extra previas a usar los datos
   *
   * @param $form FormController
   */
  // parent::resFormRevalidate( $form );


  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {

    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      $rExtModel = new RExtPanoramaModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
      else {
        if( $rExtModel->save() === false ) {
          $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
        }
      }
    }

    // if( !$form->existErrors() ) {
    //   foreach( $this->taxonomies as $tax ) {
    //     $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
    //     if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
    //       $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
    //     }
    //   }
    // }

    // Guardo los datos tipo File
    $fileFields = [ 'panoramicImage' ];
    foreach( $fileFields as $fileField ) {
      $rExtFileField = $this->addPrefix( $fileField );
      if( !$form->existErrors() && $form->isFieldDefined( $rExtFileField ) ) {
        $setFile = $this->defResCtrl->setFormFiledata( $form, $rExtFileField, $fileField, $rExtModel );
        if( $rExtModel->save() === false ) {
          $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
        }
      }
    }

    if( !$form->existErrors() ) {
      if( $rExtModel->save() === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }
  }


  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource )


  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo( $resId = false ) {
    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( !empty( $rExtViewBlockInfo['data'] ) ) {
      $rExtViewBlockInfo['template']['full'] = new Template();
      $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $rExtViewBlockInfo['template']['full']->addClientScript( 'js/ExplorerPanoramaView.js', 'rextPanorama' );
      $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextPanorama' );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtPanoramaController
