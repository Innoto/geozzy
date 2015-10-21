<?php
rextAppLugar::autoIncludes();
rextContact::autoIncludes();

class RTypeAppLugarController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeAppLugarController::__construct' );

    parent::__construct( $defResCtrl, new rtypeAppLugar() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeAppLugarController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    // Extensión lugar
    $rTypeExtNames[] = 'rextAppLugar';
    $this->rExtCtrl = new RExtAppLugarController( $this );
    $rExtFieldNames = $this->rExtCtrl->manipulateForm( $form );

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Extensión contacto
    $rTypeExtNames[] = 'rextContact';
    $this->contactCtrl = new RExtContactController( $this );
    $rExtFieldNames = $this->contactCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Altero campos del form del recurso "normal"
    $form->setFieldParam( 'externalUrl', 'label', __( 'Home URL' ) );

    return( $rTypeFieldNames );
  } // function manipulateForm()


  /**
   * Cambios en el reparto de elementos para las distintas columnas del Template de Admin
   *
   * @param $formBlock Template Contiene el form y los datos cargados
   * @param $template Template Contiene la estructura de columnas para Admin
   * @param $adminViewResource AdminViewResource Acceso a los métodos usados en Admin
   * @param $adminColsInfo Array Organización de elementos establecida por defecto
   *
   * @return Array Información de los elementos de cada columna
   */
  public function manipulateAdminFormColumns( Template $formBlock, Template $template, AdminViewResource $adminViewResource, Array $adminColsInfo ) {

    // Extraemos los campos de la extensión Lugar que irán a otro bloque y los desasignamos
    $formCategorization = $adminViewResource->extractFormBlockFields( $adminColsInfo['col8']['main']['0'], array( 'rExtAppLugar_rextAppLugarType') );

    if( $formCategorization ) {
       $formPartBlock = $this->defResCtrl->setBlockPartTemplate($formCategorization);
       $adminColsInfo['col4']['categorization'] = array( $formPartBlock, __( 'Categorization' ), false );
    }

    // Extraemos los campos de la extensión Contacto que irán a la otra columna y los desasignamos
    $formContact1 = $adminViewResource->extractFormBlockFields( $formBlock, array( 'rExtContact_address', 'rExtContact_city', 'rExtContact_cp', 'rExtContact_province', 'rExtContact_phone', 'rExtContact_email', 'externalUrl', 'rExtContact_timetable') );
    $adminColsInfo['col8']['contact1'] = array();

    if( $formContact1 ) {
      $formPartBlock = $this->defResCtrl->setBlockPartTemplate($formContact1);
      $adminColsInfo['col8']['contact1'] = array( $formPartBlock, __( 'Contact' ), false );
    }

    // Extraemos de nuevo los campos de localización y le añadimos Cómo llegar de la extensión contacto para visualizarlos en el mismo bloque
    $formLatLon = $adminViewResource->extractFormBlockFields( $formBlock, array( 'locLat', 'locLon', 'defaultZoom', 'rExtContact_directions' ) );
    if( $formLatLon ) {
      $formPartBlock = $this->defResCtrl->setBlockPartTemplate($formLatLon);
      $adminColsInfo['col8']['location'] = array( $formPartBlock , __('Location'), 'fa-archive' );
    }

    // Resordenamos los bloques de acuerdo al diseño
    $adminColsInfoOrd = array();
    $adminColsInfoOrd['col8']['main'] = $adminColsInfo['col8']['main'];
    $adminColsInfoOrd['col8']['contact1'] = $adminColsInfo['col8']['contact1'];
    $adminColsInfoOrd['col8']['location'] = $adminColsInfo['col8']['location'];
    $adminColsInfoOrd['col8']['multimedia'] = $adminColsInfo['col8']['multimedia'];
    $adminColsInfoOrd['col8']['collections'] = $adminColsInfo['col8']['collections'];
    $adminColsInfoOrd['col8']['seo'] = $adminColsInfo['col8']['seo'];

    $adminColsInfoOrd['col4']['publication'] = $adminColsInfo['col4']['publication'];
    $adminColsInfoOrd['col4']['image'] = $adminColsInfo['col4']['image'];
    $adminColsInfoOrd['col4']['categorization'] = $adminColsInfo['col4']['categorization'];
    $adminColsInfoOrd['col4']['info'] = $adminColsInfo['col4']['info'];

    return $adminColsInfoOrd;
  }

  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeAppLugarController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppLugarController( $this );
      $this->rExtCtrl->resFormRevalidate( $form );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormRevalidate( $form );
    }
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppLugarController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppLugarController( $this );
      $this->rExtCtrl->resFormProcess( $form, $resource );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppLugarController: resFormSuccess()" );

    $this->rExtCtrl = new RExtAppLugarController( $this );
    $this->rExtCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeAppLugarController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppLugar' );

    $this->rExtCtrl = new RExtAppLugarController( $this );
    $rExtBlock = $this->rExtCtrl->getViewBlock( $resBlock );

    if( $rExtBlock ) {
      $template->addToBlock( 'rextAppLugar', $rExtBlock );
      $template->assign( 'rExtBlockNames', array( 'rextAppLugar' ) );
    }
    else {
      $template->assign( 'rextAppLugar', false );
      $template->assign( 'rExtBlockNames', false );
    }

    return $template;
  }

} // class RTypeAppLugarController
