<?php


interface RTypeInterface {

  /**
   * Alteramos el objeto form. del recursoBase para adaptarlo a las necesidades del RType
   *
   * Alteramos el objeto $form:
   *   - Pasamos el control a cada una de las extensiones
   *   - Añadimos campos, valores y reglas propios del RType
   *   - Podemos alterar campos, valores y reglas existentes
   * Por defecto: Llama a cada extensión sin cambiar nada más.
   *
   * @param $form FormController Objeto form. del recursoBase
   */
  public function manipulateForm( FormController $form );

  /**
   * Preparamos los datos para visualizar las distintas partes que forman el RType
   *
   * Creamos un Array con todos la información del RType y sus RExt:
   *   - 'template' Array de objetos Template ofrecidos por el RType. Por defecto usamos 'full'
   *   - 'data' => Array con todos los datos del RType co formato 'fieldName' => 'value'
   *   - 'dataForm' => Array con contenidos HTML del formulario del RType
   *   - 'objForm' => FormController
   *   - 'ext' => Array con el resultado del manipulateForm de cada RExt
   * Por defecto se cargan todos sin crear ningún Template
   * Ejemplo de respuesta:
   * $formBlockInfo = array(
   *   'template' => array(
   *     'full' => new Template()
   *   ),
   *   'data' => $this->defResCtrl->getResourceData( $resId ),
   *   'dataForm' => array(
   *     'formId' => $form->getId(),
   *     'formOpen' => $form->getHtmpOpen(),
   *     'formFieldsArray' => $form->getHtmlFieldsArray(),
   *     'formFieldsHiddenArray' => array(),
   *     'formFields' => $form->getHtmlFieldsAndGroups(),
   *     'formClose' => $form->getHtmlClose(),
   *     'formValidations' => $form->getScriptCode()
   *   )
   *   'objForm' => $form,
   *   'ext' => array()
   * );
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'objForm' => FormController, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form );

  /**
   * Validaciones extra previas a usar los datos
   *
   * Por defecto solo pasamos el control al resFormRevalidate de cada RExt
   * Normalmente no hay que hacer nada
   * Permite revisar los datos del formulario despues del submit y añadir errores al $form:
   *   - $form->addFormError( 'Msg. de error global del formulario' );
   *   - $form->addFieldError( $fieldName, 'Msg. de error para un campo del formulario' );
   *
   * @param $form FormController Objeto form. del recurso
   */
  public function resFormRevalidate( FormController $form );

  /**
   * Creación-Edicion-Borrado de los elementos del recurso segun el RType
   *
   * @param $form FormController Objeto form. del recurso
   * @param $resource ResourceModel Objeto form. del recurso
   */
  public function resFormProcess( FormController $form, ResourceModel $resource );

  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource );

  /**
   * Preparamos los datos para visualizar el Recurso con sus cambios y sus extensiones
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'ext' => array }
   */
  public function getViewBlockInfo( $resId = false );

} // interface RTypeInterface


class RTypeController {

  public $defResCtrl = null;
  public $rTypeModule = null;
  public $rTypeName = 'RTypeNameUnknown';
  public $rExts = false;

  public $cacheQuery = false; // false, true or time in seconds (0: never expire)


  /**
   * Inicializamos FormController defResCtrl, rTypeName, RTypeController rTypeModule y Array rExts
   *
   * @param $defResCtrl FormController
   * @param $rTypeModule RTypeController
   */
  public function __construct( $defResCtrl, $rTypeModule ) {
    // error_log( __METHOD__.': '.$rTypeModule->name.' - '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );

    $this->defResCtrl = $defResCtrl;
    if( isset( $defResCtrl->cacheQuery ) ) {
      $this->cacheQuery = $defResCtrl->cacheQuery;
    }

    $this->rTypeModule = $rTypeModule;
    $this->rTypeName = $rTypeModule->name;

    if( property_exists( $rTypeModule, 'rext' ) && is_array( $rTypeModule->rext ) && count( $rTypeModule->rext ) > 0 ) {
      $this->rExts = [];
      // Cargamos los autoIncludes de los RExt de este RType si se han activado en el setup.project
      foreach( $rTypeModule->rext as $rExtName ) {
        if( class_exists( $rExtName ) ) {
          $this->rExts[] = $rExtName;
          $rExtName::autoIncludes();
        }
      }
    }
  }

  /**
   * Alteramos el objeto form. del recursoBase para adaptarlo a las necesidades del RType
   *
   * Alteramos el objeto $form:
   *   - Pasamos el control a cada una de las extensiones
   *   - Añadimos campos, valores y reglas propios del RType
   *   - Podemos alterar campos, valores y reglas existentes
   * Por defecto: Llama a cada extensión sin cambiar nada más.
   *
   * @param $form FormController Objeto form. del recursoBase
   */
  public function manipulateForm( FormController $form ) {
    // error_log( __METHOD__.': '. $this->rTypeName );

    // Lanzamos los manipulateForm de los RExt de este RType
    if( isset( $this->rExts ) && is_array( $this->rExts ) && count( $this->rExts ) ) {
      foreach( $this->rExts as $rExtName ) {
        $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
        $rExtName::load( 'controller/'.$rExtCtrlName.'.php' );
        $rExtCtrl = new $rExtCtrlName( $this );
        $rExtCtrl->manipulateForm( $form );
      }
    }
  }

  /**
   * Preparamos los datos para visualizar las distintas partes que forman el RType
   *
   * Creamos un Array con todos la información del RType y sus RExt:
   *   - 'template' Array de objetos Template ofrecidos por el RType. Por defecto usamos 'full'
   *   - 'data' => Array con todos los datos del RType co formato 'fieldName' => 'value'
   *   - 'dataForm' => Array con contenidos HTML del formulario del RType
   *   - 'objForm' => FormController
   *   - 'ext' => Array con el resultado del manipulateForm de cada RExt
   * Se cargan todos sin crear ningún Template
   * Ejemplo de respuesta:
   * $formBlockInfo = array(
   *   'template' => array(
   *     'full' => new Template()
   *   ),
   *   'data' => $this->defResCtrl->getResourceData( $resId ),
   *   'dataForm' => array(
   *     'formId' => $form->getId(),
   *     'formOpen' => $form->getHtmpOpen(),
   *     'formFieldsArray' => $form->getHtmlFieldsArray(),
   *     'formFieldsHiddenArray' => array(),
   *     'formFields' => $form->getHtmlFieldsAndGroups(),
   *     'formClose' => $form->getHtmlClose(),
   *     'formValidations' => $form->getScriptCode()
   *   )
   *   'objForm' => $form,
   *   'ext' => array()
   * );
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'objForm' => FormController, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    // error_log( __METHOD__.': '. $this->rTypeName );

    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false,
      'objForm' => $form,
      'ext' => array()
    );

    $formBlockInfo['dataForm'] = array(
      'formId' => $form->getId(),
      'formOpen' => $form->getHtmpOpen(),
      'formFieldsArray' => $form->getHtmlFieldsArray(),
      'formFieldsHiddenArray' => array(),
      'formFields' => $form->getHtmlFieldsAndGroups(),
      'formCaptcha' => $form->getHtmlCaptcha(),
      'formClose' => $form->getHtmlClose(),
      'formValidations' => $form->getScriptCode()
    );

    if( $resId = $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->defResCtrl->getResourceData( $resId );
    }

    // Lanzamos los getFormBlockInfo de los RExt de este RType
    if( isset( $this->rExts ) && is_array( $this->rExts ) && count( $this->rExts ) ) {
      foreach( $this->rExts as $rExtName ) {
        // $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
        // $rExtName::load( 'controller/'.$rExtCtrlName.'.php' );
        // $rExtCtrl = new $rExtCtrlName( $this );
        // $rExtFormViewInfo = $rExtCtrl->getFormBlockInfo( $form );
        // $formBlockInfo['ext'][ $rExtCtrl->rExtName ] = $rExtFormViewInfo;

        $rExtViewName = 'RE'.mb_strcut( $rExtName, 2 ).'View';
        $rExtName::load( 'view/'.$rExtViewName.'.php' );
        $rExtView = new $rExtViewName( $this );
        $formBlockInfo['ext'][ $rExtName ] = $rExtView->getFormBlockInfo( $form );
      }
    }



    // TEMPLATE panel estado de publicacion
    $templates['publication'] = new Template();
    $templates['publication']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['publication']->assign( 'title', __( 'Display mode' ) );
    $templates['publication']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'published'/*, 'weight'*/ );
    $templates['publication']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel SEO
    $templates['seo'] = new Template();
    $templates['seo']->setTpl( 'rTypeFormSeoPanel.tpl', 'geozzy' );
    $templates['seo']->assign( 'title', __( 'SEO' ) );
    $templates['seo']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'urlAlias' ),
      $form->multilangFieldNames( 'urlAdminAlias' ),
      $form->multilangFieldNames( 'headKeywords' ),
      $form->multilangFieldNames( 'headDescription' ),
      $form->multilangFieldNames( 'headTitle' )
    );
    $templates['seo']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel image
    $templates['image'] = new Template();
    $templates['image']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['image']->assign( 'title', __( 'Select a image' ) );
    $templates['image']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'image' );
    $templates['image']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel Localizacion
    $templates['location'] = new Template();
    $templates['location']->setTpl( 'rTypeFormLocationPanel.tpl', 'geozzy' );
    $templates['location']->assign( 'title', __( 'Location' ) );
    $templates['location']->assign( 'res', $formBlockInfo );


    // TEMPLATE panel multimedia
    $templates['multimedia'] = new Template();
    $templates['multimedia']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['multimedia']->assign( 'title', __( 'Multimedia galleries' ) );
    $templates['multimedia']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'multimediaGalleries', 'addMultimediaGalleries' );
    $templates['multimedia']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel collections
    $templates['collections'] = new Template();
    $templates['collections']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['collections']->assign( 'title', __( 'Collections of related resources' ) );
    $templates['collections']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'collections', 'addCollections' );
    $templates['collections']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel Informacion
    $templates['info'] = new Template();
    $templates['info']->setTpl( 'rTypeFormInfoPanel2.tpl', 'geozzy' );
    $templates['info']->assign( 'title', __( 'Information' ) );
    $templates['info']->assign( 'res', $formBlockInfo );
    $timeCreation = gmdate( 'd/m/Y', strtotime($formBlockInfo['data']['timeCreation']) );

    if( isset($formBlockInfo['data']['user']) ) {
      $userModel = new UserModel();
      $userCreate = $userModel->listItems([
        'filters' => [ 'id' => $formBlockInfo['data']['user'] ],
        'cache' => $this->cacheQuery
      ])->fetch();

      if($userCreate){
        $userCreateLogin = $userCreate->getter('login');
        $templates['info']->assign( 'create', [ 'time' =>$timeCreation, 'user' => $userCreateLogin ] );
      }
    }
    $templates['info']->assign( 'timeCreation', $timeCreation );

    if( isset($formBlockInfo['data']['userUpdate']) ) {
      $userModel = new UserModel();
      $userUpdate = $userModel->listItems([
        'filters' => [ 'id' => $formBlockInfo['data']['userUpdate'] ],
        'cache' => $this->cacheQuery
      ])->fetch();

      $userUpdateName = is_object( $userUpdate ) ? $userUpdate->getter('login') : __( 'Unknown' );
      $timeLastUpdate = gmdate('d/m/Y', strtotime($formBlockInfo['data']['timeLastUpdate']));
      $templates['info']->assign( 'update', [ 'time' =>$timeLastUpdate, 'user' => $userUpdateName ] );
    }
    $templates['info']->assign( 'res', $formBlockInfo );
    $templates['info']->assign( 'formFieldsNames', $formFieldsNames );



    /*
     * Paneles de extensiones opcionales
     */


    // TEMPLATE panel comment
    if( isset( $formBlockInfo['ext']['rextComment']['template']['adminExt'] ) ) {
      $templates['comment'] = new Template();
      $templates['comment']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
      $templates['comment']->assign( 'title', __( 'Comments' ) );
      $templates['comment']->setFragment( 'blockContent', $formBlockInfo['ext']['rextComment']['template']['adminExt'] );
    }


    // TEMPLATE panel rextView
    if( isset( $formBlockInfo['ext']['rextView']['template']['basic'] ) ) {
      $templates['rextView'] = new Template();
      $templates['rextView']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
      $templates['rextView']->assign( 'title', __( 'View' ) );
      $templates['rextView']->setFragment( 'blockContent', $formBlockInfo['ext']['rextView']['template']['basic'] );
    }


    // TEMPLATE panel social network
    if( isset( $formBlockInfo['ext']['rextSocialNetwork']['template']['basic'] ) ) {
      $templates['social'] = new Template();
      $templates['social']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
      $templates['social']->assign( 'title', __( 'Social Networks' ) );
      $templates['social']->setFragment( 'blockContent', $formBlockInfo['ext']['rextSocialNetwork']['template']['basic'] );
    }


    // TEMPLATE panel contacto
    if( isset( $formBlockInfo['ext']['rextContact']['template']['basic'] ) ) {
      $templates['contact'] = new Template();
      $templates['contact']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
      $templates['contact']->assign( 'title', __( 'Contact' ) );
      $templates['contact']->setFragment( 'blockContent', $formBlockInfo['ext']['rextContact']['template']['basic'] );
    }


    // TEMPLATE panel event
    if( isset( $formBlockInfo['ext']['rextEvent']['template']['full'] ) ) {
      $templates['event'] = new Template();
      $templates['event']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
      $templates['event']->assign( 'title', __( 'Event' ) );
      $templates['event']->setFragment( 'blockContent', $formBlockInfo['ext']['rextEvent']['template']['full'] );
    }


    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }

  /**
   * Validaciones extra previas a usar los datos
   *
   * Por defecto solo pasamos el control al resFormRevalidate de cada RExt
   * Normalmente no hay que hacer nada
   * Permite revisar los datos del formulario despues del submit y añadir errores al $form:
   *   - $form->addFormError( 'Msg. de error global del formulario' );
   *   - $form->addFieldError( $fieldName, 'Msg. de error para un campo del formulario' );
   *
   * @param $form FormController Objeto form. del recurso
   */
  public function resFormRevalidate( FormController $form ) {
    if( !$form->existErrors() ) {
      // Lanzamos los resFormRevalidate de los RExt de este RType
      if( isset( $this->rExts ) && is_array( $this->rExts ) && count( $this->rExts ) ) {
        foreach( $this->rExts as $rExtName ) {
          $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
          $rExtName::load( 'controller/'.$rExtCtrlName.'.php' );
          $rExtCtrl = new $rExtCtrlName( $this );
          $rExtFormViewInfo = $rExtCtrl->resFormRevalidate( $form );
        }
      }
    }
  }

  /**
   * Creación-Edicion-Borrado de los elementos del recurso segun el RType
   *
   * Hay que crear/guardar los datos del RType. Se lanza resFormProcess() de cada RExt
   * Normalmente en el RType
   * Si hay errores, es necesario registrarlos en $form para parar el proceso y notificarlos:
   *   - $form->addFormError( 'Msg. de error global del formulario' );
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    if( !$form->existErrors() ) {
      // Lanzamos los resFormProcess de los RExt de este RType
      if( isset( $this->rExts ) && is_array( $this->rExts ) && count( $this->rExts ) ) {
        foreach( $this->rExts as $rExtName ) {
          $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
          $rExtName::load( 'controller/'.$rExtCtrlName.'.php' );
          $rExtCtrl = new $rExtCtrlName( $this );
          $rExtFormViewInfo = $rExtCtrl->resFormProcess( $form, $resource );
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
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // Lanzamos los resFormSuccess de los RExt de este RType
    if( isset( $this->rExts ) && is_array( $this->rExts ) && count( $this->rExts ) ) {
      foreach( $this->rExts as $rExtName ) {
        $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
        $rExtName::load( 'controller/'.$rExtCtrlName.'.php' );
        $rExtCtrl = new $rExtCtrlName( $this );
        $rExtCtrl->resFormSuccess( $form, $resource );
      }
    }
  }

  /**
   * Preparamos los datos para visualizar el Recurso con sus cambios y sus extensiones
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'ext' => array }
   */
  public function getViewBlockInfo( $resId = false ) {
    $viewBlockInfo = array(
      'template' => array(
        'full' => new Template() // Definimos un Template 'full' por defecto
      ),
      'data' => $this->defResCtrl->getResourceData( $resId ),
      'ext' => array()
    );

    // Lanzamos los getViewBlockInfo de los RExt de este RType
    // y preasignamos su template['full'] a un bloque por defecto
    if( isset( $this->rExts ) && is_array( $this->rExts ) && count( $this->rExts ) ) {
      foreach( $this->rExts as $rExtName ) {

        // $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
        // $rExtCtrl = new $rExtCtrlName( $this );
        // $viewBlockInfo['ext'][ $rExtName ] = $rExtCtrl->getViewBlockInfo();
        $rExtViewName = 'RE'.mb_strcut( $rExtName, 2 ).'View';
        $rExtName::load( 'view/'.$rExtViewName.'.php' );
        $rExtView = new $rExtViewName( $this );
        $viewBlockInfo['ext'][ $rExtName ] = $rExtView->getViewBlockInfo( $resId = false );

        if( isset( $viewBlockInfo['ext'][ $rExtName ]['template']['full'] ) ) {
          $viewBlockInfo['template']['full']->addToFragment( $rExtName.'Block',
            $viewBlockInfo['ext'][ $rExtName ]['template']['full'] );
        }
        else {
          $viewBlockInfo['template']['full']->assign( $rExtName.'Block', false );
        }
      }
    }

    $viewBlockInfo['template']['full']->assign( 'res',
      array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );

    return $viewBlockInfo;
  }





  public function cloneTo( $resFromObj, $resToObj ) {
    error_log( __METHOD__.': '.$this->rTypeName );
    $result = true;

    // De momento los RType no tienen Modelos ni Taxonomias


    // Lanzamos los cloneTo de los RExt de este RType
    if( !empty($this->rExts) ) {

      $rTypeToRExts = $this->rExts;

      $rTypeFromCtrl = $this->defResCtrl->getRTypeCtrl( $resFromObj->getter('rTypeId') );
      $rTypeFromRExts = !empty($rTypeFromCtrl->rExts) ? $rTypeFromCtrl->rExts : false;

      $rTypeCommonRExts = ($rTypeFromRExts) ? array_intersect( $rTypeFromRExts, $rTypeToRExts ) : false;

      if( !empty($rTypeCommonRExts) ) {
        foreach( $rTypeCommonRExts as $rExtName ) {
          $rExtCtrlName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
          $rExtName::load( 'controller/'.$rExtCtrlName.'.php' );
          $rExtCtrl = new $rExtCtrlName( $this );
          $result &= $rExtCtrl->cloneTo( $resFromObj, $resToObj );
        }
      }
    }


    return $result;
  }


} // class RTypeController
