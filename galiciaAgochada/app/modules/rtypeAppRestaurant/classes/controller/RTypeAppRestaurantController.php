<?php

class RTypeAppRestaurantController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeAppRestaurant() );
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

    $eatCtrl = new RExtEatAndDrinkController( $this );

    // Elimino los campos de la extensión que no quiero usar
    $form->removeField('rextEatAndDrink_capacity');

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

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
    global $C_LANG;

    // Cargamos la informacion del form, los datos y lanzamos los getFormBlockInfo de las extensiones
    $formBlockInfo = parent::getFormBlockInfo( $form );

    $templates = $formBlockInfo['template'];

    $eatCtrl = new RExtEatAndDrinkController( $this );
    $zonaCtrl = new RExtAppZonaController( $this );
    $contactCtrl = new RExtContactController( $this );



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
    $formFieldsNames = $eatCtrl->prefixArray( array( 'reservationURL', 'reservationPhone' ) );
    $templates['reservation']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel participacion
    if(class_exists( 'rextParticipation' ) && in_array('rextParticipation', $this->rExts)) {
      $templates['infoParticipation'] = new Template();
      $templates['infoParticipation']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
      $templates['infoParticipation']->assign( 'title', __( 'Info Participation' ) );
      $templates['infoParticipation']->setFragment( 'blockContent', $formBlockInfo['ext']['rextParticipation']['template']['full'] );
    }

    // TEMPLATE panel categorization
    $templates['categorization'] = new Template();
    $templates['categorization']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['categorization']->assign( 'title', __( 'Categorization' ) );
    $templates['categorization']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $eatCtrl->prefixArray( array('eatanddrinkType', 'eatanddrinkSpecialities', 'averagePrice') );
    $formFieldsNames[] = $zonaCtrl->addPrefix('rextAppZonaType');
    $templates['categorization']->assign( 'formFieldsNames', $formFieldsNames );



    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );

    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['contact'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['social'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['reservation'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['location'] );
    if(class_exists( 'rextComment' ) && in_array('rextComment', $this->rExts)) {
      $templates['adminFull']->addToFragment( 'col8', $templates['comment'] );
    }
    $templates['adminFull']->addToFragment( 'col8', $templates['multimedia'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['collections'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToFragment( 'col4', $templates['publication'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['image'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['categorization'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['info'] );
    if(class_exists( 'rextParticipation' ) && in_array('rextParticipation', $this->rExts)) {
      $templates['adminFull']->addToFragment( 'col4', $templates['infoParticipation'] );
    }


    // TEMPLATE con todos los pasos para participacion
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if(class_exists( 'rextParticipation' ) && in_array('rextParticipation', $this->rExts)) {
      $participationCtrl = new RExtParticipationController( $this );
      $participationBlockInfo = $formBlockInfo;
      $partForm = clone $participationBlockInfo['objForm'];

      $partForm->setFieldParam( 'title_'.$C_LANG, 'label', __('Cómo se chama o lugar?') );
      $partForm->setFieldParam( 'title_'.$C_LANG, 'class', '' );
      $partForm->setValidationRule( 'title_'.$C_LANG, 'required', true );
      $partForm->setFieldParam( 'mediumDescription_'.$C_LANG, 'label', __('Descríbeo brevemente') );
      $partForm->setFieldParam( 'mediumDescription_'.$C_LANG, 'class', '' );
      $partForm->setValidationRule( 'mediumDescription_'.$C_LANG, 'required', true );
      $partForm->setFieldParam( 'rextEatAndDrink_eatanddrinkType', 'label', __('Cómo clasificarías este lugar?') );
      $partForm->setValidationRule( 'rextEatAndDrink_eatanddrinkType', 'required', true );
      $partForm->setFieldParam( 'rExtContact_city', 'label', __('¿En qué localidade se encontra?') );
      $partForm->setFieldParam( 'rExtContact_province', 'label', __('¿En qué provincia se encontra?') );
      $partForm->setFieldParam( 'rExtContact_url', 'label', __('¿Ten páxina web?') );
      $partForm->setFieldParam( 'rExtContact_phone', 'label', __('¿Teléfono?') );
      $partForm->setFieldParam( 'locLat', 'type', 'reserved');
      $partForm->setFieldParam( 'locLon', 'type', 'reserved');
      $partForm->setFieldParam( 'defaultZoom', 'type', 'reserved');
      $partForm->setFieldParam( 'published', 'type', 'reserved');
      $partForm->setFieldParam( 'published', 'value', false);
      $partForm->setFieldValue( 'rExtParticipation_participation', true);

      $partForm->setField( 'acceptCond',  array( 'type' => 'checkbox', 'options' => array( 'accept' => __( 'Acepto as <span>condicións do servicio e a política de privacidade</span>' ) ) ) );
      $partForm->setValidationRule( 'acceptCond', 'required', true );

      //$form->removeValidationRules('topics');
      $partForm->removeField( 'externalUrl');
      $partForm->removeField( 'rextEatAndDrink_reservationURL');
      $partForm->removeField( 'rextEatAndDrink_averagePrice');
      $partForm->removeField( 'rextEatAndDrink_eatanddrinkSpecialities');
      $partForm->removeField( 'rExtContact_email');
      $partForm->removeField( 'rExtSocialNetwork_activeFb');
      $partForm->removeField( 'rExtSocialNetwork_activeTwitter');
      $partForm->removeField( 'rExtSocialNetwork_activeGplus');
      $partForm->removeField( 'urlAlias_es');

      $partForm->captchaEnable( true );

      $participationBlockInfo['dataForm'] = array(
        'formOpen' => $partForm->getHtmpOpen(),
        'formFieldsArray' => $partForm->getHtmlFieldsArray(),
        'formFieldsHiddenArray' => array(),
        'formFields' => $partForm->getHtmlFieldsAndGroups(),
        'formCaptcha' => $partForm->getHtmlCaptcha(),
        'formClose' => $partForm->getHtmlClose(),
        'formValidations' => $partForm->getScriptCode()
      );

      $participationBlockInfo['objForm'] = $partForm;
      //$participationBlockInfo['objForm']->saveToSession();

      $formFieldsNamesStp1 = array( 'title_'.$C_LANG, 'mediumDescription_'.$C_LANG, 'rTypeIdName' );
      $formFieldsNamesStp2 = $eatCtrl->prefixArray( array('eatanddrinkType') );
      $formFieldsNamesStp3 = $contactCtrl->prefixArray( array('city', 'province', 'url', 'phone') );
      $formFieldsNamesStp4 = array( 'image' );
      $formFieldsNamesStp5 = $participationCtrl->prefixArray( array('observation') );
      array_push( $formFieldsNamesStp5, 'acceptCond' );

      $templates['participationFull'] = new Template();
      $templates['participationFull']->setTpl( 'participationModal.tpl', 'rtypeAppRestaurant' );
      $templates['participationFull']->assign( 'headTitle', __( 'Participation Form' ) );
      $templates['participationFull']->assign( 'res', $participationBlockInfo );
      $templates['participationFull']->assign( 'formFieldsNamesStp1', $formFieldsNamesStp1 );
      $templates['participationFull']->assign( 'formFieldsNamesStp2', $formFieldsNamesStp2 );
      $templates['participationFull']->assign( 'formFieldsNamesStp3', $formFieldsNamesStp3 );
      $templates['participationFull']->assign( 'formFieldsNamesStp4', $formFieldsNamesStp4 );
      $templates['participationFull']->assign( 'formFieldsNamesStp5', $formFieldsNamesStp5 );

      $templates['participationWV'] = new Template();
      $templates['participationWV']->setTpl( 'participationWV.tpl', 'rtypeAppRestaurant' );
      $participationBlockInfo['header'] = false;
      $participationBlockInfo['footer'] = false;
      $templates['participationWV']->assign( 'res', $participationBlockInfo );
      $templates['participationWV']->assign( 'formFieldsNamesStp1', $formFieldsNamesStp1 );
      $templates['participationWV']->assign( 'formFieldsNamesStp2', $formFieldsNamesStp2 );
      $templates['participationWV']->assign( 'formFieldsNamesStp3', $formFieldsNamesStp3 );
      $templates['participationWV']->assign( 'formFieldsNamesStp4', $formFieldsNamesStp4 );
      $templates['participationWV']->assign( 'formFieldsNamesStp5', $formFieldsNamesStp5 );

      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
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

    $template = $viewBlockInfo['template']['full'];
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppRestaurant' );

    $collectionArrayInfo = $this->defResCtrl->getCollectionBlockInfo( $viewBlockInfo['data'][ 'id' ] );

    $multimediaArray = false;
    $collectionArray = false;
    if ($collectionArrayInfo){
      foreach ($collectionArrayInfo as $key => $collectionInfo){
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
          foreach ($arrayMultimediaBlock as $multimediaBlock){
            $template->addToFragment( 'multimediaGalleries', $multimediaBlock );
          }
        }
      }

      if ($collectionArray){
        $arrayCollectionBlock = $this->defResCtrl->goOverCollections( $collectionArray, $collectionType = 'base' );
        if ($arrayCollectionBlock){
          foreach ($arrayCollectionBlock as $collectionBlock){
            $template->addToFragment( 'collections', $collectionBlock );
          }
        }
      }
    }
    if(class_exists( 'rextComment' ) && in_array('rextComment', $this->rExts)) {
      $template->addToFragment( 'rextCommentAverageBlock', $viewBlockInfo['ext']['rextComment']['template']['headerAverage'] );
    }
    $viewBlockInfo['template']['full'] = $template;

    return $viewBlockInfo;
  }

} // class RTypeAppRestaurantController
