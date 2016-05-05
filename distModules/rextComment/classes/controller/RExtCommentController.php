<?php
geozzy::load( 'controller/RExtController.php' );

class RExtCommentController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl = false ){
    if($defRTypeCtrl){
      parent::__construct( $defRTypeCtrl, new rextComment(), 'rExtComment_' );
    }
    $this->numericFields = array( );
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

    $rExtModel = new ResourceCommentModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

      // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
      $termsGroupedIdName = $this->defResCtrl->getTermsInfoByGroupIdName( $resId );
      if( $termsGroupedIdName !== false ) {
        foreach( $this->taxonomies as $tax ) {
          if( isset( $termsGroupedIdName[ $tax[ 'idName' ] ] ) ) {
            $rExtData[ $tax['idName'] ] = $termsGroupedIdName[ $tax[ 'idName' ] ];
          }
        }
      }
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
      'activeComment' => array(
        'params' => array( 'label' => __( 'Comentarios activados' ) ),
        'rules' => array( 'maxlength' => 1 )
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Valadaciones extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

    // Si es una edicion, añadimos el ID y cargamos los datos
    $valuesArray = $this->getRExtData( $form->getFieldValue( 'id' ) );
    if( $valuesArray ) {
      $valuesArray = $this->prefixArrayKeys( $valuesArray );
      $form->setField( $this->addPrefix( 'id' ), array( 'type' => 'reserved', 'value' => null ) );

      // Limpiando la informacion de terms para el form
      if( $this->taxonomies ) {
        foreach( $this->taxonomies as $tax ) {
          $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
          if( isset( $valuesArray[ $taxFieldName ] ) && is_array( $valuesArray[ $taxFieldName ] ) ) {
            $taxFieldValues = array();
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
    $form->setField( $this->addPrefix( 'FieldNames' ), array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

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
  // parent::getFormBlockInfo( $form );


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

      $rExtModel = new ResourceCommentModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    if( !$form->existErrors() ) {
      foreach( $this->taxonomies as $tax ) {
        $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
        if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
          $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
        }
      }
    }

    if( !$form->existErrors() ) {
      $saveResult = $rExtModel->save();
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
  public function getViewBlockInfo() {
    $rExtViewBlockInfo = parent::getViewBlockInfo();

    if( $rExtViewBlockInfo['data'] ) {

      $perms = $this->getCommentPermissions( $rExtViewBlockInfo['data']['resource'] );

      $rExtViewBlockInfo['template']['full'] = new Template();
      if(in_array('comment', $perms)){
        $rExtViewBlockInfo['template']['full']->assign( 'commentButton', true );
      }
      $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextComment' );
      $rExtViewBlockInfo['template']['full']->addClientScript('js/commentList.js', 'rextComment');
    }

    return $rExtViewBlockInfo;
  }

  /**
   * Metodo que comprueba si es posible enviar un comentario o sugerencia
   *
   * @return Array $permissions{ 'comment', 'suggest' }
   */
  public function getCommentPermissions($id) {
    $perms = array();

    $resourceModel = new ResourceModel();
    $res = $resourceModel->listItems(
      array(
        'filters'=> array('id' => $id),
        'affectsDependences' => array('ResourcetypeModel')
      )
    )->fetch();

    $rtype = $res->getterDependence('rTypeId', 'ResourcetypeModel');
    $commentRules = Cogumelo::getSetupValue( 'mod:geozzy:resource:commentRules');
    if($commentRules){
      if(array_key_exists($rtype[0]->getter('idName'), $commentRules)){
        $perms = $commentRules[$rtype[0]->getter('idName')]['ctype'];
      }else{
        $perms = $commentRules['default']['ctype'];
      }
    }
    if(in_array('comment', $perms)){
      $resExtCommentModel = new ResourceCommentModel();
      $resExtComment = $resExtCommentModel->listItems(
      array('filters'=> array('resource' => $id)))->fetch();

      if($resExtComment && !$resExtComment->getter('activeComment')){
        $unsetKey = array_search('comment', $perms);
        unset($perms[$unsetKey]);
      }
    }
    return $perms;
  }

  /**
   * Metodo que comprueba moderacion de un comentario y te devuelve si se publica o no
   *
   * @return Bool true or false
   */
  public function commentPublish($id) {
    $publish = false;

    $resourceModel = new ResourceModel();
    $res = $resourceModel->listItems(
      array(
        'filters'=> array('id' => $id),
        'affectsDependences' => array('ResourcetypeModel')
      )
    )->fetch();

    $rtype = $res->getterDependence('rTypeId', 'ResourcetypeModel');
    $commentRules = Cogumelo::getSetupValue( 'mod:geozzy:resource:commentRules');
    if($commentRules){
      if(array_key_exists($rtype[0]->getter('idName'), $commentRules)){
        $moderation = $commentRules[$rtype[0]->getter('idName')]['moderation'];
      }else{
        $moderation = $commentRules['default']['moderation'];
      }
      switch ($moderation) {
        case 'none':
          $publish = true;
          break;
        case 'verified':
          $useraccesscontrol = new UserAccessController();
          $userSess = $useraccesscontrol->getSessiondata();
          if($userSess){
            $publish = ($userSess['data']['verified'] == 1) ? true : false;
          }
          break;
      }
    }
    return $publish;
  }

} // class RExtAccommodationController
