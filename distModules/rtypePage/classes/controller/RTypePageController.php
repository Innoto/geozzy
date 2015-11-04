<?php
rextView::autoIncludes();
rextContact::autoIncludes();

class RTypePageController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypePageController::__construct' );

    parent::__construct( $defResCtrl, new rtypePage() );
  }


  private function newRExtContr() {

    return new RExtViewController( $this );
  }

  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypePageController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextView';
    $this->rExtCtrl = $this->newRExtContr();
    $rExtFieldNames = $this->rExtCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

    // Extensión contacto
    $rTypeExtNames[] = 'rextContact';
    $this->contactCtrl = new RExtContactController( $this );
    $rExtFieldNames = $this->contactCtrl->manipulateForm( $form );

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

    // Extraemos los campos de la extensión Vista
    $viewAlternativeMode = $adminViewResource->extractFormBlockFields( $formBlock, array( 'rExtView_viewAlternativeMode') );
    if( $viewAlternativeMode ) {
      $formPartBlock = $this->defResCtrl->setBlockPartTemplate($viewAlternativeMode);
      $adminColsInfo['col4']['mode'] = array( $formPartBlock, __( 'View alternative mode' ), false );
    }

    // Extraemos los campos de la extensión Contacto que irán a la otra columna y los desasignamos
    $formContact1 = $adminViewResource->extractFormBlockFields( $formBlock, array( 'rExtContact_address', 'rExtContact_city', 'rExtContact_cp', 'rExtContact_province', 'rExtContact_phone', 'rExtContact_email', 'externalUrl', 'rExtContact_timetable') );
    $formContact2 = $adminViewResource->extractFormBlockFields( $formBlock, array( 'rExtContact_directions') );
    $adminColsInfo['col8']['contact1'] = array();

    if( $formContact1 ) {
      $formPartBlock = $this->defResCtrl->setBlockPartTemplate($formContact1);
      $adminColsInfo['col8']['contact1'] = array( $formPartBlock, __( 'Contact' ), false );
    }

    // Componemos el bloque geolocalización
    $templateBlock = $formBlock->getTemplateVars('formFieldsArray');
    $resourceLocLat = $templateBlock['locLat'];
    $resourceLocLon = $templateBlock['locLon'];
    $resourceDefaultZoom = $templateBlock['defaultZoom'];
    $resourceDirections = $templateBlock['rExtContact_directions'];


    $locationData = '<div class="row">'.$resourceLocLat.'</div>
                     <div class="row">'.$resourceLocLon.'</div>
                     <div class="row">'.$resourceDefaultZoom.'</div>
                     <div class="row btn btn-primary col-md-offset-3">'.__("Automatic Location").'</div>';


    $locAll = '<div class="location">
            <div class="row"><div class="col-lg-6 mapContainer"><div class="descMap">Haz click en el lugar donde se ubica el recurso<br>Podrás arrastrar y soltar la localización</div></div>
            <div class="col-lg-6 locationData">'.$locationData.'</div></div>
            <div class="locationDirections">'.$resourceDirections.'</div>
            </div>';

    $adminColsInfo['col8']['location'] = array( $locAll, __( 'Location' ), 'fa-globe' );

    // Resordenamos los bloques de acuerdo al diseño
    $adminColsInfoOrd = array();
    $adminColsInfoOrd['col8']['main'] = $adminColsInfo['col8']['main'];
    $adminColsInfoOrd['col8']['location'] = $adminColsInfo['col8']['location'];
    $adminColsInfoOrd['col8']['multimedia'] = $adminColsInfo['col8']['multimedia'];
    $adminColsInfoOrd['col8']['collections'] = $adminColsInfo['col8']['collections'];
    $adminColsInfoOrd['col8']['seo'] = $adminColsInfo['col8']['seo'];

    $adminColsInfoOrd['col4']['publication'] = $adminColsInfo['col4']['publication'];
    $adminColsInfoOrd['col4']['mode'] = $adminColsInfo['col4']['mode'];
    $adminColsInfoOrd['col4']['image'] = $adminColsInfo['col4']['image'];
    $adminColsInfoOrd['col4']['info'] = $adminColsInfo['col4']['info'];

    return $adminColsInfoOrd;
  }



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypePageController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = $this->newRExtContr();
      $this->rExtCtrl->resFormRevalidate( $form );
    }

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypePageController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = $this->newRExtContr();
      $this->rExtCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypePageController: resFormSuccess()" );

    $this->rExtCtrl = $this->newRExtContr();
    $this->rExtCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypePageController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypePage' );

    $this->rExtCtrl = $this->newRExtContr();
    $viewBlock = $this->rExtCtrl->getViewBlock( $template );
    // IMPORTANTE: rExtView seguramente cambie o .tpl de $template
    // pasando de rTypeViewBlock.tpl de rtypePage

    if( $viewBlock ) {
      $template->addToBlock( 'rextView', $viewBlock );
      $template->assign( 'rExtBlockNames', array( 'rextView' ) );
    }
    else {
      $template->assign( 'rextView', false );
      $template->assign( 'rExtBlockNames', false );
    }

    return $template;
  }

} // class RTypePageController
