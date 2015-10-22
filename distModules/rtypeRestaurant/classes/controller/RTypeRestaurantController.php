<?php
rextEatAndDrink::autoIncludes();
rextContact::autoIncludes();

class RTypeRestaurantController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeRestaurantController::__construct' );

    parent::__construct( $defResCtrl, new rtypeRestaurant() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeRestaurantController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextEatAndDrink';
    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $rExtFieldNames = $this->eatCtrl->manipulateForm( $form );

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

    $form->setFieldParam( 'externalUrl', 'label', __( 'Home URL' ) );

    // Valadaciones extra
    // $form->setValidationRule( 'restaurantName_'.$form->langDefault, 'required' );

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

    // Extraemos los campos de la extensión EatandDrink que irán a otro bloque y los desasignamos
    $formReservation = $adminViewResource->extractFormBlockFields( $formBlock, array( 'rextEatAndDrink_reservationURL', 'rextEatAndDrink_reservationPhone', 'rextEatAndDrink_reservationEmail') );
    $formCategorization = $adminViewResource->extractFormBlockFields( $adminColsInfo['col8']['main']['0'], array( 'rextEatAndDrink_eatanddrinkType', 'rextEatAndDrink_eatanddrinkSpecialities', 'rextEatAndDrink_capacity', 'rextEatAndDrink_averagePrice') );

    if( $formReservation ) {
       $formPartBlock =$this->defResCtrl->setBlockPartTemplate($formReservation);
       $adminColsInfo['col8']['reservation'] = array( $formPartBlock, __('Reservation'), 'fa-archive' );
    }
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

    // Componemos el bloque geolocalización
    $templateBlock = $formBlock->getTemplateVars('formFieldsArray');
    $resourceLocLat = $templateBlock['locLat'];
    $resourceLocLon = $templateBlock['locLon'];
    $resourceDefaultZoom = $templateBlock['defaultZoom'];
    $resourceDirections = $templateBlock['rExtContact_directions'];


    $locationData = '<div class="row">'.$resourceLocLat.'</div>
                     <div class="row">'.$resourceLocLon.'</div>
                     <div class="row">'.$resourceDefaultZoom.'</div>
                     <div class="row btn btn-primary col-md-offset-3">'.__("Authomatic Location").'</div>';


    $locAll = '<div class="location">
            <div class="row"><div class="col-lg-6"><div class="descMap">Haz click en el lugar donde se ubica el recurso<br>Podrás arrastrar y soltar la localización</div><div id="resourceLocationMap"></div></div>
            <div class="col-lg-6 locationData">'.$locationData.'</div></div>
            <div class="locationDirections">'.$resourceDirections.'</div>
            </div>';

    $adminColsInfo['col8']['location'] = array( $locAll, __( 'Location' ), 'fa-globe' );

    // Resordenamos los bloques de acuerdo al diseño
    $adminColsInfoOrd = array();
    $adminColsInfoOrd['col8']['main'] = $adminColsInfo['col8']['main'];
    $adminColsInfoOrd['col8']['contact1'] = $adminColsInfo['col8']['contact1'];
    $adminColsInfoOrd['col8']['reservation'] = $adminColsInfo['col8']['reservation'];
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
    // error_log( "RTypeRestaurantController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->eatCtrl = new RExtEatAndDrinkController( $this );
      $this->eatCtrl->resFormRevalidate( $form );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormRevalidate( $form );
    }

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeRestaurantController: resFormProcess()" );
    if( !$form->existErrors() ) {
      $this->eatCtrl = new RExtEatAndDrinkController( $this );
      $this->eatCtrl->resFormProcess( $form, $resource );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeRestaurantController: resFormSuccess()" );

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $this->eatCtrl->resFormSuccess( $form, $resource );

    $this->contactCtrl = new RExtContactController( $this );
    $this->contactCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeRestaurantController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeRestaurant' );

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $eatBlock = $this->eatCtrl->getViewBlock( $resBlock );

    $this->contactCtrl = new RExtContactController( $this );
    $contactBlock = $this->contactCtrl->getViewBlock( $resBlock );

    if( $eatBlock ) {
      $template->addToBlock( 'rextEatAndDrink', $eatBlock );
      $template->assign( 'rExtBlockNames', array( 'rextEatAndDrink' ) );
    }
    else {
      $template->assign( 'rextEatAndDrink', false );
      $template->assign( 'rExtBlockNames', false );
    }

    if( $contactBlock ) {
      $template->addToBlock( 'rextContact', $contactBlock );
      $template->assign( 'rExtContactBlockNames', array( 'rextContact' ) );
    }
    else {
      $template->assign( 'rextContact', false );
      $template->assign( 'rExtContactBlockNames', false );
    }


    return $template;
  }

} // class RTypeRestaurantController
