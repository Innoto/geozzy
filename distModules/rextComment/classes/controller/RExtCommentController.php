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
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'value' => '1', 'options'=> array( '1' => __('Active comment') )),
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
  public function getFormBlockInfo( FormController $form ) {
    $formBlockInfo = parent::getFormBlockInfo( $form );
    $templates = $formBlockInfo['template'];

    $templates['adminExt'] = new Template();
    $templates['adminExt']->setTpl( 'rExtFormBlock.tpl', 'rextComment' );
    $templates['adminExt']->assign( 'rExt', $formBlockInfo );
    $templates['adminExt']->assign( 'rExtName', $this->rExtName );

    $rControl = new ResourceController();
    $commentTypeOptions = $rControl->getOptionsTax('commentType');
    $templates['adminExt']->assign( 'commentTypeOptions', $commentTypeOptions );
    $templates['adminExt']->addClientScript('js/rextAdminCommentList.js', 'rextComment');

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

      $rExtModel = new ResourceCommentModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }else{

        if($valuesArray['activeComment'] !== '1'){
          $saveResult = $rExtModel->save();
          if( $saveResult === false ) {
            $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
          }
        }else{
          if($rExtModel->getter('id')){
            $saveResult = $rExtModel->delete();
            if( $saveResult === false ) {
              $form->addFormError( 'No se ha podido borrar el recurso. (rExtModel)','formError' );
            }
          }
        }

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
    $rExtViewBlockInfo['template']['full'] = new Template();

    $resID = $this->defResCtrl->resObj->getter('id');

    $averageVotesModel = new AverageVotesViewModel();
    $resAverageData = $averageVotesModel->listItems( array('filters' => array('id' => $resID) ))->fetch();
    if( $resAverageData ) {
      $resAverageVotes = $resAverageData->getter('averageVotes');
      $resNumberVotes = $resAverageData->getter('commentsVotes');
    }

    $perms = $this->getCommentPermissions( $resID );
    if( $perms && in_array( 'comment', $perms ) ) {
      $rExtViewBlockInfo['template']['full']->assign( 'commentButton', true );
    }
    $rExtViewBlockInfo['template']['full']->assign( 'resID', $resID);
    if($resAverageData){
      $rExtViewBlockInfo['template']['full']->assign( 'resAverageVotes', $resAverageVotes);
      $rExtViewBlockInfo['template']['full']->assign( 'resNumberVotes', $resNumberVotes);
    }
    $commentModel = new commentModel();
    $commentCount = $commentModel->listCount( array('filters' => array('resource' => $resID) ));
    if( !in_array('comment', $perms) && $commentCount === 0){
      $rExtViewBlockInfo['template']['full']->assign( 'commentEmpty', true);
    }
    $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextComment' );
    $rExtViewBlockInfo['template']['full']->addClientScript('js/commentList.js', 'rextComment');

    $rExtViewBlockInfo['template']['headerAverage'] = new Template();
    if($resAverageData){
      $rExtViewBlockInfo['template']['headerAverage']->assign( 'resAverageVotes', $resAverageVotes);
    }
    $rExtViewBlockInfo['template']['headerAverage']->setTpl( 'rExtAverageBlock.tpl', 'rextComment' );
    return $rExtViewBlockInfo;
  }

  /**
   * Metodo que comprueba si es posible enviar un comentario o sugerencia
   *
   * @return Array $permissions{ 'comment', 'suggest' }
   */
  public function getCommentPermissions( $resId ) {

    $permsTmp = $this->getPermissions( $resId );
    $perms = isset( $permsTmp[ $resId ]['ctype'] ) ? $permsTmp[ $resId ]['ctype'] : array();

    /*
      $resourceModel = new ResourceModel();
      $res = $resourceModel->listItems(
        array(
          'filters'=> array('id' => $resId),
          'affectsDependences' => array('ResourcetypeModel')
        )
      )->fetch();

      if( $res ) {
        $perms = array();
        $rtype = $res->getterDependence( 'rTypeId', 'ResourcetypeModel' );
        $commentRules = Cogumelo::getSetupValue( 'mod:geozzy:resource:commentRules' );

        if( $commentRules ) {
          if( array_key_exists($rtype[0]->getter('idName'), $commentRules) ){
            $perms = $commentRules[ $rtype[0]->getter('idName') ]['ctype'];
          }
          else {
            $perms = $commentRules['default']['ctype'];
          }
        }

        if( in_array('comment', $perms) ) {
          $resExtCommentModel = new ResourceCommentModel();
          $resExtComment = $resExtCommentModel->listItems(
            array('filters'=> array('resource' => $resId)) )->fetch();

          if( $resExtComment && !$resExtComment->getter('activeComment') ) {
            $unsetKey = array_search( 'comment', $perms );
            unset( $perms[$unsetKey] );
          }
        }
      }
    */

    return $perms;
  }

  /**
   * Metodo que comprueba si es posible enviar un comentario o sugerencia
   *
   * @return Array $permissions{ 'comment', 'suggest' }
   */
  public function getPermissions( $resId ) {
    $commRules = array();

    $setup = Cogumelo::getSetupValue( 'mod:geozzy:resource:commentRules' );
    if( !isset( $setup['default'] ) ) {
      if( !is_array( $setup ) ) {
        $setup = array();
      }
      $setup['default'] = array(
        'moderation' => 'all', // none|verified|all
        'ctype' => array() // 'comment','suggest'
      );
    }

    $filters = array();
    if( $resId ) {
      $inArray = explode( ',', $resId );
      if( count( $inArray ) > 1 ) {
        $filters['idIn'] = $inArray;
      }
      else {
        $filters['id'] = intval( $resId );
      }
    }

    $suggestTypeTerms = $this->getSuggestTypeTerms();
    $suggestTypeTermsArray = array();
    foreach( $suggestTypeTerms as $term ) {
      $suggestTypeTermsArray[ $term['id'] ] = $term['name'];
    }

    $resCommModel = new ResourceCommentViewModel();
    $resPermsList = $resCommModel->listItems( array( 'filters'=> $filters ) );


    while( $resPerms = $resPermsList->fetch() ) {
      $resId = $resPerms->getter( 'id' );
      $rTypeIdName = $resPerms->getter( 'rTypeIdName' );

      $perms = isset($setup[ $rTypeIdName ]) ? $setup[ $rTypeIdName ] : $setup['default'];

      if( in_array('comment', $perms['ctype']) ) {
        if( $resPerms->getter('activeComment') ) {
          $unsetKey = array_search( 'comment', $perms['ctype'] );
          unset( $perms['ctype'][ $unsetKey ] );
        }
      }

      if( in_array('suggest', $perms['ctype']) ) {
        $perms['suggestType'] = $suggestTypeTermsArray;
      }

      $commRules[ $resId ] = $perms;
    }

    return $commRules;
  }

  /**
   * Metodo que comprueba moderacion de un comentario y te devuelve si se publica o no
   *
   * @return Bool true or false
   */
  public function commentPublish( $resId ) {
    $publish = false;

    $resourceModel = new ResourceModel();
    $res = $resourceModel->listItems(
      array(
        'filters'=> array('id' => $resId),
        'affectsDependences' => array('ResourcetypeModel')
      )
    )->fetch();

    $rtype = $res->getterDependence('rTypeId', 'ResourcetypeModel');
    $commentRules = Cogumelo::getSetupValue( 'mod:geozzy:resource:commentRules');
    if($commentRules){
      if(array_key_exists($rtype[0]->getter('idName'), $commentRules)){
        $moderation = $commentRules[$rtype[0]->getter('idName')]['moderation'];
      }
      else{
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

  public function getCommentTypeTerms( $typeIdNames = array( 'comment', 'suggest' ) ) {
    $commentTypeTerms = array();

    $taxModelControl = new TaxonomygroupModel();
    $termModelControl = new TaxonomytermModel();
    // Data Options Comment Type
    $commentTypeTax = $taxModelControl->listItems( array('filters' => array('idName' => 'commentType')) )->fetch();
    $commentTypeTermsList = $termModelControl->listItems(
      array('filters' =>
        array( 'taxgroup' => $commentTypeTax->getter('id'), 'idNames' => $typeIdNames )
      )
    );

    while( $commentTypeTermObj = $commentTypeTermsList->fetch() ) {
      $commentTypeTerms[ $commentTypeTermObj->getter('id') ] = $commentTypeTermObj->getter('idName');
    }

    return $commentTypeTerms;
  }

  public function getCommentTypeTermId( $typeIdName ) {
    $commentTypeTermId = false;

    $commentTypeTermInfo = $this->getCommentTypeTerms( $typeIdName );
    if( count( $commentTypeTermInfo ) === 1 ) {
      $commentTypeTermKeys = array_keys( $commentTypeTermInfo );
      $commentTypeTermId = array_shift( $commentTypeTermKeys );
    }

    return $commentTypeTermId;
  }

  public function getSuggestTypeTerms() {
    $suggestTypeTerms = array();

    $taxModelControl = new TaxonomygroupModel();
    $termModelControl = new TaxonomytermModel();

    $suggestTypeTax = $taxModelControl->listItems( array('filters' => array('idName' => 'suggestType')) )->fetch();
    $suggestTypeTermsList = $termModelControl->listItems( array('filters' =>
      array('taxgroup' => $suggestTypeTax->getter('id'))) );

    while( $suggestTypeTermObj = $suggestTypeTermsList->fetch() ) {
      $suggestTypeTerms[ $suggestTypeTermObj->getter('id') ] = array (
        'id' => $suggestTypeTermObj->getter('id'),
        'idName' => $suggestTypeTermObj->getter('idName'),
        'name' => $suggestTypeTermObj->getter('name')
      );
    }

    return $suggestTypeTerms;
  }

} // class RExtAccommodationController
