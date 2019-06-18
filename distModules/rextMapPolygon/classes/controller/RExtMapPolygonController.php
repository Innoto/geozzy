<?php
geozzy::load( 'controller/RExtController.php' );

class RExtMapPolygonController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rExtMapPolygon(), 'rExtMapPolygon_' );
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

    // @todo Esto ten que controlar os idiomas
    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtMapPolygonModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ), 'cache' => $this->cacheQuery ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
    }

    return $rExtData;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {

        $rExtFieldNames = array();

        $fieldsInfo = array(
          'polygonGeometry' => array(
            'rules' => array( )
          )
        );

        $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

        // Si es una edicion, añadimos el ID y cargamos los datos
        $valuesArray = $this->getRExtData( $form->getFieldValue( 'id' ) );
        if( $valuesArray ) {
          $valuesArray = $this->prefixArrayKeys( $valuesArray );
          $form->setField( $this->addPrefix( 'id' ), array( 'type' => 'reserved', 'value' => null ) );
        }

        $form->loadArrayValues( $valuesArray );
        //($valuesArray);exit;
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
        $form->setField( $this->addPrefix( 'FieldNames' ), array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

/*
        $form->setSuccess( 'onFileUpload', 'adminRextRoutesJs.fileUpload' );

        $form->saveToSession();*/

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
     $templates = $formBlockInfo['template'];

     //$templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
     $templates['full']->setTpl( 'rExtFormBlock.tpl', 'rextMapPolygon' );
     $templates['full']->addClientScript('js/adminRextMapPolygon.js', 'rextMapPolygon');
     $templates['full']->assign( 'rExtName', $this->rExtName );
     $templates['full']->assign( 'rExt', $formBlockInfo );
     $formBlockInfo['template'] = $templates;

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
      //$numericFields = array( 'averagePrice', 'capacity' );
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

//var_dump(json_decode('['.$valuesArray[ 'polygonGeometry' ].']' ));
      //Route start LOCATION
      /*
      if( isset( $valuesArray[ 'polygonGeometry' ] ) && is_array(json_decode('['.$valuesArray[ 'polygonGeometry' ].']' )) ) {
        Cogumelo::load( 'coreModel/DBUtils.php' );
        $valuesArray[ 'polygonGeometry' ] = DBUtils::encodeGeometry(
          array(
            'type' => 'POLYGON',
            'data'=> json_decode('['.$valuesArray[ 'polygonGeometry' ].']' )
          )
        );

      }
*/


      $this->rExtModel = new RExtMapPolygonModel( $valuesArray );
      if( $this->rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }





    if( !$form->existErrors() ) {
      $saveResult = $this->rExtModel->save();
      if( $saveResult === false ) {
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


     return $rExtViewBlockInfo;
   }


} // class RExtRoutesController
