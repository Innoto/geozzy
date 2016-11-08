<?php

class RTypeAppLugarController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeAppLugar() );
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

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');
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

    $lugarCtrl = new RExtAppLugarController( $this );
    $zonaCtrl = new RExtAppZonaController( $this );

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
    $formFieldsNames = $lugarCtrl->prefixArray( array( 'reservationURL', 'reservationPhone' ) );
    $templates['reservation']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel audioguide
    $templates['audioguide'] = new Template();
    $templates['audioguide']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['audioguide']->assign( 'title', __( 'Audioguide' ) );
    $templates['audioguide']->setFragment( 'blockContent', $formBlockInfo['ext']['rextAudioguide']['template']['basic'] );

    // TEMPLATE panel categorization
    $templates['categorization'] = new Template();
    $templates['categorization']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['categorization']->assign( 'title', __( 'Categorization' ) );
    $templates['categorization']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $lugarCtrl->prefixArray( array('rextAppLugarType') );
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
    $templates['adminFull']->addToFragment( 'col4', $templates['audioguide'] );
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

    $template = $viewBlockInfo['template']['full'];
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppLugar' );

    /* Cargamos los bloques de colecciones */
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
            $multimediaBlock->assign( 'max', 6 );
            $multimediaBlock->setTpl('appEspazoNaturalMultimediaViewBlock.tpl', 'rtypeAppEspazoNatural');
            $template->addToFragment( 'multimediaGalleries', $multimediaBlock );
          }
        }
      }

      if ($collectionArray){
        $arrayCollectionBlock = $this->defResCtrl->goOverCollections( $collectionArray, $collectionType = 'base'  );
        if ($arrayCollectionBlock){
          foreach( $arrayCollectionBlock as $collectionBlock ) {
            $collectionBlock->setTpl('appEspazoNaturalCollectionViewBlock.tpl', 'rtypeAppEspazoNatural');
            $template->addToFragment( 'collections', $collectionBlock );
          }
        }
      }
    }

    if( class_exists( 'rextComment' ) && in_array('rextComment', $this->rExts) ) {
     $template->addToFragment( 'rextCommentAverageBlock', $viewBlockInfo['ext']['rextComment']['template']['headerAverage'] );
    }

    $template->addClientScript('js/rextAudioguideAdmin.js', 'rextAudioguide');
    $viewBlockInfo['template']['full'] = $template;

    return $viewBlockInfo;
  }

} // class RTypeAppLugarController
