<?php

class RTypeAppHotelController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeAppHotel() );
  }


  /**
   * Alteramos el objeto form. del recursoBase para adaptarlo a las necesidades del RType
   *
   * @param $form FormController Objeto form. del recursoBase
   *
   * @return array $rTypeFieldNames
   */
  public function manipulateForm( FormController $form ) {

    // Lanzamos los manipulateForm de las extensiones
    parent::manipulateForm( $form );


    // Elimino los campos de rextAccommodation que no quiero usar
    $accomCtrl = new RExtAccommodationController( $this );
    $rExtAccommodationRemove = $accomCtrl->prefixArray( array( 'singleRooms', 'doubleRooms',
      'tripleRooms', 'familyRooms', 'beds', 'accommodationBrand', 'accommodationUsers' ) );
    $form->removeField( $rExtAccommodationRemove );

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam( 'topics', 'type', 'reserved' );
    $form->setFieldParam( 'starred', 'type', 'reserved' );
    $form->removeValidationRules( 'topics' );
    $form->removeValidationRules( 'starred' );

    // Altero campos del form del recurso "normal"
    $form->setFieldParam( 'externalUrl', 'label', __( 'Home URL' ) );
  } // function manipulateForm()


  /**
   * Preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {

    // Cargamos la informacion del form, los datos y lanzamos los getFormBlockInfo de las extensiones
    $formBlockInfo = parent::getFormBlockInfo( $form );
    $templates = $formBlockInfo['template'];

    // Necesito estos controles
    $accomCtrl = new RExtAccommodationController( $this );
    $zonaCtrl = new RExtAppZonaController( $this );
    if( class_exists( 'RExtAccommodationReserveController' ) && in_array( 'rextAccommodationReserve', $this->rExts ) ) {
      $accomReserveCtrl = new RExtAccommodationReserveController( $this );
    }


    // TEMPLATE panel principa del form. Contiene los elementos globales del form.
    $templates['formBase'] = new Template();
    $templates['formBase']->setTpl( 'rTypeFormBase.tpl', 'geozzy' );
    $templates['formBase']->assign( 'title', __('Main Resource information') );
    $templates['formBase']->assign( 'res', $formBlockInfo );

    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'title' ),
      $form->multilangFieldNames( 'shortDescription' ),
      $form->multilangFieldNames( 'mediumDescription' ),
      $form->multilangFieldNames( 'content' )
    );
    $formFieldsNames[] = 'externalUrl';
    $formFieldsNames[] = 'rTypeIdName';
    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel reservas
    $templates['reservation'] = new Template();
    $templates['reservation']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['reservation']->assign( 'title', __( 'Reservation' ) );
    $templates['reservation']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $accomCtrl->prefixArray( [ 'reservationURL', 'reservationPhone', 'reservationEmail' ] );
    if( isset( $accomReserveCtrl ) ) {
      $formFieldsNames = array_merge( $formFieldsNames, $accomReserveCtrl->prefixArray( [ 'channel', 'idRelate' ] ) );
    }
    $templates['reservation']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel categorization
    $templates['categorization'] = new Template();
    $templates['categorization']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['categorization']->assign( 'title', __( 'Categorization' ) );
    $templates['categorization']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $accomCtrl->prefixArray(array( 'accommodationType', 'accommodationCategory',
      'averagePrice', 'accommodationFacilities', 'accommodationServices'));
    $formFieldsNames[] = $zonaCtrl->addPrefix('rextAppZonaType');
    $templates['categorization']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );
    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['contact'] );

    if(class_exists( 'rextComment' ) && in_array('rextComment', $this->rExts)) {
      $templates['adminFull']->addToFragment( 'col8', $templates['comment'] );
    }

    $templates['adminFull']->addToFragment( 'col8', $templates['social'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['reservation'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['location'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['multimedia'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['collections'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToFragment( 'col4', $templates['publication'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['image'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['categorization'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['info'] );


    // TEMPLATE en bruto con todos los elementos del form
    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rTypeFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'res', $formBlockInfo );


    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }


  /**
   * Validaciones extra previas a usar los datos del recurso
   *
   * @param $form FormController Objeto form. del recurso
   */
  // parent::resFormRevalidate( $form );


  /**
   * Creación-Edicion-Borrado de los elementos del recurso segun el RType
   *
   * @param $form FormController Objeto form. del recurso
   * @param $resource ResourceModel Objeto form. del recurso
   */
  // parent::resFormProcess( $form, $resource );


  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource );



  /**
   * Preparamos los datos para visualizar el Recurso con sus cambios y sus extensiones
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'ext' => array }
   */
  public function getViewBlockInfo() {

    // Preparamos los datos para visualizar el Recurso con sus extensiones
    $viewBlockInfo = parent::getViewBlockInfo();


    // $template = new Template();
    $template = $viewBlockInfo['template']['full'];
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppHotel' );

    // $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );

    $collectionArrayInfo = $this->defResCtrl->getCollectionBlockInfo( $viewBlockInfo['data'][ 'id' ] );

    $multimediaArray = false;
    $collectionArray = false;
    if ($collectionArrayInfo){
      foreach( $collectionArrayInfo as $key => $collectionInfo ) {
        if ($collectionInfo['col']['collectionType'] == 'multimedia'){ // colecciones multimedia
          $multimediaArray[$key] = $collectionInfo;
        }
        else{ // resto de colecciones
          $collectionArray[$key] = $collectionInfo;
        }
      }

      if ($multimediaArray){
        $arrayMultimediaBlock = $this->defResCtrl->goOverCollections( $multimediaArray, $collectionType = 'multimedia' );
        if ($arrayMultimediaBlock){
          foreach( $arrayMultimediaBlock as $multimediaBlock ) {
            $template->addToFragment( 'multimediaGalleries', $multimediaBlock );
          }
        }
      }

      if ($collectionArray){
        $arrayCollectionBlock = $this->defResCtrl->goOverCollections( $collectionArray, $collectionType = 'base'  );
        if ($arrayCollectionBlock){
          foreach( $arrayCollectionBlock as $collectionBlock ) {
            $template->addToFragment( 'collections', $collectionBlock );
          }
        }
      }
    }

    if(class_exists( 'rextComment' ) && in_array('rextComment', $this->rExts)) {
      $template->addToFragment( 'rextCommentAverageBlock', $viewBlockInfo['ext']['rextComment']['template']['headerAverage'] );
    }

    if( in_array('rextAccommodationReserve', $this->rExts) ) {
      $template->addToFragment( 'rextAccommodationReserve', $viewBlockInfo['ext']['rextAccommodationReserve']['template']['full'] );
    }

    $taxtermModel = new TaxonomytermModel();

    /* Recuperamos todos los términos de la taxonomía servicios*/
    $services = $this->defResCtrl->getOptionsTax( 'accommodationServices' );
    foreach( $services as $serviceId => $serviceName ) {
      $service = $taxtermModel->listItems(array('filters'=> array('id' => $serviceId)))->fetch();
      /*Quitamos los términos de la extensión que no se usan en este proyecto*/
      if ($service->getter('idName') !== 'telefono' && $service->getter('idName') !== 'serviciodehabitacions' && $service->getter('idName') !== 'transportepublico'){
        $allServices[$serviceId]['name'] = $serviceName;
        $allServices[$serviceId]['idName'] = $service->getter('idName');
        $allServices[$serviceId]['icon'] = $service->getter('icon');
      }
    }
    $template->assign('allServices', $allServices);

    /* Recuperamos todos los términos de la taxonomía instalaciones*/
    $facilities = $this->defResCtrl->getOptionsTax( 'accommodationFacilities' );
    foreach( $facilities as $facilityId => $facilityName ) {
      $facility = $taxtermModel->listItems(array('filters'=> array('id' => $facilityId)))->fetch();
      /*Quitamos los términos de la extensión que no se usan en este proyecto*/
      if ($facility->getter('idName') !== 'bar' && $facility->getter('idName') !== 'lavadora'){
        $allFacilities[$facilityId]['name'] = $facilityName;
        $allFacilities[$facilityId]['idName'] = $facility->getter('idName');
        $allFacilities[$facilityId]['icon'] = $facility->getter('icon');
      }
    }
    $template->assign('allFacilities', $allFacilities);


    //$template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );
    $viewBlockInfo['template']['full'] = $template;

    return $viewBlockInfo;
  }

} // class RTypeAppHotelController
