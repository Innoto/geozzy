<?php
rextAccommodation::autoIncludes();
rextContact::autoIncludes();

class RTypeHotelController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeHotelController::__construct' );

    parent::__construct( $defResCtrl, new rtypeHotel() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeHotelController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    // Extensión alojamiento
    $rTypeExtNames[] = 'rextAccommodation';
    $this->accomCtrl = new RExtAccommodationController( $this );
    $rExtFieldNames = $this->accomCtrl->manipulateForm( $form );
    // Elimino los campos de la extensión que no quiero usar
    foreach( $rExtFieldNames as $i => $fieldName ) {
      if( $fieldName == 'singleRooms' || $fieldName == 'doubleRooms' || $fieldName == 'tripleRooms' || $fieldName == 'familyRooms'
          || $fieldName == 'beds' || $fieldName == 'accommodationBrand' || $fieldName == 'accommodationUsers' ) {
        $form->removeField( 'rExtAccommodation_'.$rExtFieldNames[$i] );
      }
    }

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

    // Añadir validadores extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

    return( $rTypeFieldNames );
  } // function manipulateForm()







  public function getFormBlockInfo( FormController $form ) {
    error_log( "RTypeHotelController: getFormBlockInfo()" );

    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false,
      'ext' => array()
    );

    $formBlockInfo['dataForm'] = array(
      'formOpen' => $form->getHtmpOpen(),
      'formFieldsArray' => $form->getHtmlFieldsArray(),
      'formFieldsHiddenArray' => array(),
      'formFields' => $form->getHtmlFieldsAndGroups(),
      'formClose' => $form->getHtmlClose(),
      'formValidations' => $form->getScriptCode()
    );

    if( $resId = $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->defResCtrl->getResourceData( $resId );
    }

    $this->accomCtrl = new RExtAccommodationController( $this );
    $accomViewInfo = $this->accomCtrl->getFormBlockInfo( $form );
    $viewBlockInfo['ext'][ $this->accomCtrl->rExtName ] = $accomViewInfo;

    $this->contactCtrl = new RExtContactController( $this );
    $contactViewInfo = $this->contactCtrl->getFormBlockInfo( $form );
    $viewBlockInfo['ext'][ $this->contactCtrl->rExtName ] = $contactViewInfo;


    // TEMPLATE panel principa del form. Contiene los elementos globales del form.
    $templates['formBase'] = new Template();
    $templates['formBase']->setTpl( 'rTypeFormBase.tpl', 'rtypeHotel' );
    $templates['formBase']->assign( 'title', __('Main Resource information') );
    $templates['formBase']->assign( 'res', $formBlockInfo );

    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'title' ),
      $form->multilangFieldNames( 'shortDescription' ),
      $form->multilangFieldNames( 'mediumDescription' ),
      $form->multilangFieldNames( 'content' )
    );
    $formFieldsNames[] = 'externalUrl';
    $formFieldsNames[] = 'topics';
    $formFieldsNames[] = 'starred';
    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel estado de publicacion
    $templates['publication'] = new Template();
    $templates['publication']->setTpl( 'rTypeFormDefPanel.tpl', 'rtypeHotel' );
    $templates['publication']->assign( 'title', __( 'Publication' ) );
    $templates['publication']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'published', 'weight' );
    $templates['publication']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel SEO
    $templates['seo'] = new Template();
    $templates['seo']->setTpl( 'rTypeFormDefPanel.tpl', 'rtypeHotel' );
    $templates['seo']->assign( 'title', __( 'SEO' ) );
    $templates['seo']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'urlAlias' ),
      $form->multilangFieldNames( 'headKeywords' ),
      $form->multilangFieldNames( 'headDescription' ),
      $form->multilangFieldNames( 'headTitle' )
    );
    $templates['seo']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel reservas
    $templates['reservation'] = new Template();
    $templates['reservation']->setTpl( 'rTypeFormDefPanel.tpl', 'rtypeHotel' );
    $templates['reservation']->assign( 'title', __( 'Reservation' ) );
    $templates['reservation']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'rExtAccommodation_reservationURL', 'rExtAccommodation_reservationPhone', 'rExtAccommodation_reservationEmail' );
    $templates['reservation']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );
    // COL8
    $templates['adminFull']->addToBlock( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['reservation'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToBlock( 'col4', $templates['publication'] );

/*
$cols['col8']['location'] = array( $formPartBlock , __('Location'), 'fa-archive' );
$cols['col8']['collections'] = array( $formPartBlock, __('Collections of related resources'), 'fa-th-large' );
$cols['col8']['multimedia'] = array( $formPartBlock, __('Multimedia galleries'), 'fa-th-large' );
$cols['col4']['image'] = array( $formPartBlock, __( 'Select a image' ), 'fa-file-image-o' );
$cols['col8']['location'] = array( $locAll, __( 'Location' ), 'fa-globe' );
$cols['col4']['info'] = array( $info, __( 'Information' ), 'fa-globe' );
*/

    // TEMPLATE en bruto con todos los elementos del form
    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rTypeFormBlock.tpl', 'rtypeHotel' );
    $templates['full']->assign( 'res', $formBlockInfo );


    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }






  /**
   * Defino la visualizacion del formulario
   */
  public function manipulateFormTemplate( FormController $form, Template $template ) {
    error_log( "RTypeHotelController: formToTemplate()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    return( $template );
  }

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

    // Extraemos los campos de la extensión Alojamiento que irán a otro bloque y los desasignamos
    $formReservation = $adminViewResource->extractFormBlockFields( $formBlock, array( 'rExtAccommodation_reservationURL',
      'rExtAccommodation_reservationPhone', 'rExtAccommodation_reservationEmail') );
    $formCategorization = $adminViewResource->extractFormBlockFields( $adminColsInfo['col8']['main']['0'],
      array( 'rExtAccommodation_accommodationType', 'rExtAccommodation_accommodationCategory',
        'rExtAccommodation_averagePrice', 'rExtAccommodation_accommodationFacilities',
        'rExtAccommodation_accommodationServices') );

    if( $formReservation ) {
      $formPartBlock =$this->defResCtrl->setBlockPartTemplate($formReservation);
      $adminColsInfo['col8']['reservation'] = array( $formPartBlock, __('Reservation'), 'fa-archive' );
    }
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
    // error_log( "RTypeHotelController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->accomCtrl = new RExtAccommodationController( $this );
      $this->accomCtrl->resFormRevalidate( $form );

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
    // error_log( "RTypeHotelController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->accomCtrl = new RExtAccommodationController( $this );
      $this->accomCtrl->resFormProcess( $form, $resource );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeHotelController: resFormSuccess()" );

    $this->accomCtrl = new RExtAccommodationController( $this );
    $this->accomCtrl->resFormSuccess( $form, $resource );

    $this->contactCtrl = new RExtContactController( $this );
    $this->contactCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeHotelController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeHotel' );

    $this->accomCtrl = new RExtAccommodationController( $this );
    $accomBlock = $this->accomCtrl->getViewBlock( $resBlock );

    $this->contactCtrl = new RExtContactController( $this );
    $contactBlock = $this->contactCtrl->getViewBlock( $resBlock );

    if( $accomBlock ) {
      $template->addToBlock( 'rextAccommodation', $accomBlock );
      $template->assign( 'rExtAccommodation_averagePrice', $accomBlock->tpl_vars['rExtAccommodation_averagePrice']->value );
      $template->assign( 'rExtAccommodation_reservationURL', $accomBlock->tpl_vars['rExtAccommodation_reservationURL']->value );
      $template->assign( 'rExtAccommodation_reservationPhone', $accomBlock->tpl_vars['rExtAccommodation_reservationPhone']->value );
      $template->assign( 'rExtAccommodationBlockNames', array( 'rextAccommodation' ) );
    }
    else {
      $template->assign( 'rextAccommodation', false );
      $template->assign( 'rExtAccommodationBlockNames', false );
    }

    if( $contactBlock ) {
      $template->addToBlock( 'rextContact', $contactBlock );
      $template->assign( 'rExtContact_directions', $contactBlock->tpl_vars['rExtContact_directions_'.LANG_DEFAULT]->value );
      $template->assign( 'rExtContactBlockNames', array( 'rextContact' ) );
    }
    else {
      $template->assign( 'rextContact', false );
      $template->assign( 'rExtContactBlockNames', false );
    }

    return $template;
  }



  /**
    Preparamos los datos para visualizar el Recurso
   **/
  public function getViewBlockInfo() {
    error_log( "RTypeHotelController: getViewBlockInfo()" );

    $viewBlockInfo = array(
      'template' => false,
      'data' => $this->defResCtrl->getResourceData( false, true ),
      'ext' => array()
    );

    $template = new Template();
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeHotel' );

    $this->accomCtrl = new RExtAccommodationController( $this );
    $accomViewInfo = $this->accomCtrl->getViewBlockInfo();
    $viewBlockInfo['ext'][ $this->accomCtrl->rExtName ] = $accomViewInfo;

    $this->contactCtrl = new RExtContactController( $this );
    $contactViewInfo = $this->contactCtrl->getViewBlockInfo();
    $viewBlockInfo['ext'][ $this->contactCtrl->rExtName ] = $contactViewInfo;

    $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );



    if( $accomViewInfo ) {
      if( $accomViewInfo['template'] ) {
        foreach( $accomViewInfo['template'] as $nameBlock => $templateBlock ) {
          $template->addToBlock( 'rextAccommodationBlock', $templateBlock );
        }
      }
    }
    else {
      $template->assign( 'rextAccommodationBlock', false );
    }

    if( $contactViewInfo ) {
      if( $contactViewInfo['template'] ) {
        foreach( $contactViewInfo['template'] as $nameBlock => $templateBlock ) {
          $template->addToBlock( 'rextContactBlock', $templateBlock );
        }
      }
    }
    else {
      $template->assign( 'rextContactBlock', false );
    }

    $viewBlockInfo['template'] = array( 'full' => $template );

    return $viewBlockInfo;
  }

} // class RTypeHotelController
