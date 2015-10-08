<?php
rextAccommodation::autoIncludes();


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

    $rTypeExtNames[] = 'rextAccommodation';
    $this->accomCtrl = new RExtAccommodationController( $this );
    $rExtFieldNames = $this->accomCtrl->manipulateForm( $form );
    // Elimino los campos de la extensión que no quiero usar
    foreach ($rExtFieldNames as $i => $fieldName){
      if ($fieldName == 'singleRooms' || $fieldName == 'doubleRooms' || $fieldName == 'tripleRooms' || $fieldName == 'familyRooms'
          || $fieldName == 'beds' || $fieldName == 'accommodationBrand' || $fieldName == 'accommodationUsers'){
        $form->removeField('rExtAccommodation_'.$rExtFieldNames[$i]);
      }
    }

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Altero campos del form del recurso "normal"
    $form->setFieldParam( 'externalUrl', 'label', __( 'Hotel home URL' ) );

    // Añadir validadores extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

    return( $rTypeFieldNames );
  } // function manipulateForm()


  /**
   * Defino la visualizacion del formulario
   */
  public function manipulateFormTemplate( FormController $form, Template $template ) {
    error_log( "RTypeHotelController: formToTemplate()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();


    $rTypeExtNames[] = 'rextAccommodation';
    $this->accomCtrl = new RExtAccommodationController( $this );
    //$rExtFieldNames = $this->accomCtrl->manipulateFormTemplate( $form, $template );
    //$rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );


    $template->assign( 'rTypeExtNames', $rTypeExtNames );
    $template->assign( 'rTypeFieldNames', $rTypeFieldNames );

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

    // Extraemos los campos del tipo Hotel que irán a la otra columna y los desasignamos
    $formHotel = $adminViewResource->extractFormBlockFields( $formBlock, array( 'externalUrl', 'rExtAccommodation_reservationURL', 'rExtAccommodation_reservationPhone', 'rExtAccommodation_reservationEmail') );
    $formHotel8 = $adminViewResource->extractFormBlockFields( $adminColsInfo['col8']['main']['0'], array( 'rExtAccommodation_accommodationType', 'rExtAccommodation_accommodationCategory',
                  'rExtAccommodation_averagePrice', 'rExtAccommodation_accommodationFacilities', 'rExtAccommodation_accommodationServices') );

    if( $formHotel ) {
       $formPartBlock =$this->defResCtrl->setBlockPartTemplate($formHotel);
       $adminColsInfo['col8']['reservation'] = array( $formPartBlock, __('Reservation'), 'fa-archive' );
    }

    if( $formHotel8 ) {
       $formPartBlock = $this->defResCtrl->setBlockPartTemplate($formHotel8);
       $adminColsInfo['col4']['categorization'] = array( $formPartBlock, __( 'Categorization' ), false );
    }
    //
    // echo '<pre>';
    // print_r($adminColsInfo);
    // echo '</pre>';

    // Resordenamos los bloques de acuerdo al diseño
    $adminColsInfoOrd = array();
    $adminColsInfoOrd['col8']['main'] = $adminColsInfo['col8']['main'];
    $adminColsInfoOrd['col8']['contact'] = $adminColsInfo['col8']['contact'];
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

    if( $accomBlock ) {
      $template->addToBlock( 'rextAccommodation', $accomBlock );
      $template->assign( 'rExtBlockNames', array( 'rextAccommodation' ) );
    }
    else {
      $template->assign( 'rextAccommodation', false );
      $template->assign( 'rExtBlockNames', false );
    }

    return $template;
  }

} // class RTypeHotelController
