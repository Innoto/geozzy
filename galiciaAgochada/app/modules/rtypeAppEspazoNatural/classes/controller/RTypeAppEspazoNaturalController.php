<?php
rextAppEspazoNatural::autoIncludes();
rextContact::autoIncludes();

class RTypeAppEspazoNaturalController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ) {
    error_log( 'RTypeAppEspazoNaturalController::__construct' );

    parent::__construct( $defResCtrl, new rtypeAppEspazoNatural() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeAppEspazoNaturalController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');
    // eliminamos campos de recurso
    $form->removeField('externalUrl');

    // Extensión Espazo Natural
    $rTypeExtNames[] = 'rextAppEspazoNatural';
    $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
    $rExtFieldNames = $this->rExtCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Extensión Contacto
    $rTypeExtNames[] = 'rextContact';
    $this->contactCtrl = new RExtContactController( $this );
    $rExtFieldNames = $this->contactCtrl->manipulateForm( $form );

    // eliminamos los campos de contacto que no necesitamos
    $form->removeField('rExtContact_address');
    $form->removeField('rExtContact_city');
    $form->removeField('rExtContact_cp');
    $form->removeField('rExtContact_province');
    $form->removeField('rExtContact_phone');
    $form->removeField('rExtContact_email');
    $form->removeField('rExtContact_timetable');

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );


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

    $formUtils = new FormController();

    // Extraemos los campos de la extensión Lugar que irán a otro bloque y los desasignamos
    $formCategorization = $adminViewResource->extractFormBlockFields( $adminColsInfo['col8']['main']['0'],
      array( 'rExtAppEspazoNatural_rextAppEspazoNaturalType' ) );

    if( $formCategorization ) {
      $formPartBlock = $this->defResCtrl->setBlockPartTemplate($formCategorization);
      $adminColsInfo['col4']['categorization'] = array( $formPartBlock, __( 'Categorization' ), false );
    }

    // Extraemos los campos de la extensión Contacto que irán a la otra columna y los desasignamos
    $formContact1 = $adminViewResource->extractFormBlockFields( $formBlock, array( 'rExtContact_address',
      'rExtContact_city', 'rExtContact_cp', 'rExtContact_province', 'rExtContact_phone',
      'rExtContact_email', 'externalUrl', 'rExtContact_timetable') );
    $formContact2 = $adminViewResource->extractFormBlockFields( $formBlock, $formUtils->multilangFieldNames( 'rExtContact_directions' ) );
    $adminColsInfo['col8']['contact1'] = array();

    if( $formContact1 ) {
      $formPartBlock = $this->defResCtrl->setBlockPartTemplate( $formContact1 );
      $adminColsInfo['col8']['contact1'] = array( $formPartBlock, __( 'Contact' ), false );
    }

    // Componemos el bloque geolocalización
    $templateBlock = $formBlock->getTemplateVars('formFieldsArray');
    $resourceLocLat = $templateBlock['locLat'];
    $resourceLocLon = $templateBlock['locLon'];
    $resourceDefaultZoom = $templateBlock['defaultZoom'];
    $resourceDirections = implode( "\n", $formContact2 ); // $templateBlock['rExtContact_directions'];

    $locationData = '<div class="row">'.
      '<div class="col-md-3">'.$resourceLocLat.'</div>'.
      '<div class="col-md-3">'.$resourceLocLon.'</div>'.
      '<div class="col-md-3">'.$resourceDefaultZoom.'</div>'.
      '<div class="col-md-3"><div class="automaticBtn btn btn-primary">'.__("Automatic Location").'</div></div></div>';

    $locAll = '<div class="row location">'.
        '<div class="col-lg-12 mapContainer">'.
          '<div class="descMap">Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización</div>'.
        '</div>'.
        '<div class="col-lg-12 locationData">'.$locationData.'</div>'.
        '<div class="col-lg-12 locationDirections">'.$resourceDirections.'</div>'.
      '</div>';

    $adminColsInfo['col8']['location'] = array( $locAll, __( 'Location' ), 'fa-globe' );

    // Resordenamos los bloques de acuerdo al diseño
    $adminColsInfoOrd = array();
    $adminColsInfoOrd['col8']['main'] = $adminColsInfo['col8']['main'];
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
    // error_log( "RTypeAppEspazoNaturalController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
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
    // error_log( "RTypeAppEspazoNaturalController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
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
    // error_log( "RTypeAppEspazoNaturalController: resFormSuccess()" );

    $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
    $this->rExtCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeAppEspazoNaturalController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppEspazoNatural' );

    $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
    $rExtBlock = $this->rExtCtrl->getViewBlock( $resBlock );

    if( $rExtBlock ) {
      $template->addToBlock( 'rextAppEspazoNatural', $rExtBlock );
      $template->assign( 'rExtBlockNames', array( 'rextAppEspazoNatural' ) );
    }
    else {
      $template->assign( 'rextAppEspazoNatural', false );
      $template->assign( 'rExtBlockNames', false );
    }

    return $template;
  }

} // class RTypeAppEspazoNaturalController
