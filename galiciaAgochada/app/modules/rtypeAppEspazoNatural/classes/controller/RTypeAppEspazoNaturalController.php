<?php

class RTypeAppEspazoNaturalController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ) {
    parent::__construct( $defResCtrl, new rtypeAppEspazoNatural() );
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

    $espazoCtrl = new RExtAppEspazoNaturalController( $this );
    $zonaCtrl = new RExtAppZonaController( $this );
    /*
    $this->poiCollectionCtrl = new RExtPoiCollectionController( $this );
    $poiCollectionViewInfo = $this->poiCollectionCtrl->getFormBlockInfo( $form );
    $viewBlockInfo['ext'][ $this->poiCollectionCtrl->rExtName ] = $poiCollectionViewInfo;*/

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


    // TEMPLATE panel estado de publicacion
    $templates['publication'] = new Template();
    $templates['publication']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['publication']->assign( 'title', __( 'Publication' ) );
    $templates['publication']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'published', 'weight' );
    $templates['publication']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel SEO
    $templates['seo'] = new Template();
    $templates['seo']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['seo']->assign( 'title', __( 'SEO' ) );
    $templates['seo']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'urlAlias' ),
      $form->multilangFieldNames( 'headKeywords' ),
      $form->multilangFieldNames( 'headDescription' ),
      $form->multilangFieldNames( 'headTitle' )
    );
    $templates['seo']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel contacto
    $templates['location'] = new Template();
    $templates['location']->setTpl( 'rTypeFormLocationPanel.tpl', 'geozzy' );
    $templates['location']->assign( 'title', __( 'Location' ) );
    $templates['location']->assign( 'res', $formBlockInfo );
    $templates['location']->assign('directions', $form->multilangFieldNames( 'rExtContact_directions' ));

    // TEMPLATE panel localización
    $templates['contact'] = new Template();
    $templates['contact']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['contact']->assign( 'title', __( 'Contact' ) );
    $templates['contact']->setFragment( 'blockContent', $formBlockInfo['ext']['rextContact']['template']['basic'] );

    // TEMPLATE panel social network
    $templates['social'] = new Template();
    $templates['social']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['social']->assign( 'title', __( 'Social Networks' ) );
    //$templates['social']->setFragment( 'blockContent', $socialViewInfo['template']['basic'] );
    $templates['social']->setFragment( 'blockContent', $formBlockInfo['ext']['rextSocialNetwork']['template']['basic'] );

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

    // TEMPLATE panel poicollection
    $templates['poiCollection'] = new Template();
    $templates['poiCollection']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['poiCollection']->assign( 'title', __( 'POI collection' ) );
    $templates['poiCollection']->setFragment( 'blockContent', $formBlockInfo['ext']['rextPoiCollection']['template']['full'] );

    // TEMPLATE panel image
    $templates['image'] = new Template();
    $templates['image']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['image']->assign( 'title', __( 'Select a image' ) );
    $templates['image']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'image' );
    $templates['image']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel categorization
    $templates['categorization'] = new Template();
    $templates['categorization']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['categorization']->assign( 'title', __( 'Categorization' ) );
    $templates['categorization']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $espazoCtrl->prefixArray( array('rextAppEspazoNaturalType'));
    $formFieldsNames[] = $zonaCtrl->addPrefix('rextAppZonaType');
    $templates['categorization']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel cuadro informativo
    $templates['info'] = new Template();
    $templates['info']->setTpl( 'rTypeFormInfoPanel.tpl', 'geozzy' );
    $templates['info']->assign( 'title', __( 'Information' ) );
    $templates['info']->assign( 'res', $formBlockInfo );

    $resourceType = new ResourcetypeModel();
    $type = $resourceType->listItems(array('filters' => array('id' => $formBlockInfo['data']['rTypeId'])))->fetch();
    if($type){
      $templates['info']->assign( 'rType', $type->getter('name_es') );
    }

    $timeCreation = gmdate('d/m/Y', strtotime($formBlockInfo['data']['timeCreation']));

    $templates['info']->assign( 'timeCreation', $timeCreation );
    if(isset($formBlockInfo['data']['userUpdate'])){
      $userModel = new UserModel();
      $userUpdate = $userModel->listItems( array( 'filters' => array('id' => $formBlockInfo['data']['userUpdate']) ) )->fetch();
      $userUpdateName = $userUpdate->getter('name');
      $timeLastUpdate = gmdate('d/m/Y', strtotime($formBlockInfo['data']['timeLastUpdate']));
      $templates['info']->assign( 'timeLastUpdate', $timeLastUpdate.' ('.$userUpdateName.')' );
    }
    if(isset($formBlockInfo['data']['averageVotes'])){
      $templates['info']->assign( 'averageVotes', $formBlockInfo['data']['averageVotes']);
    }
    /* Temáticas */
    if(isset($formBlockInfo['data']['topicsName'])){
      $templates['info']->assign( 'resourceTopicList', $formBlockInfo['data']['topicsName']);
    }
    $templates['info']->assign( 'res', $formBlockInfo );
    $templates['info']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );
    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['contact'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['social'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['location'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['poiCollection'] );
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

    //$template = new Template();
    $template = $viewBlockInfo['template']['full'];
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppEspazoNatural' );
    // $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );

    /* Cargamos los bloques de colecciones */
    $collectionArrayInfo = $this->defResCtrl->getCollectionBlockInfo( $viewBlockInfo['data'][ 'id' ] );

    $multimediaArray = false;
    $collectionArray = false;
    if ($collectionArrayInfo){
      foreach ($collectionArrayInfo as $key => $collectionInfo){
        switch($collectionInfo['col']['collectionType']){
          case 'multimedia':
            $multimediaArray[$key] = $collectionInfo;
            break;
          case 'base':
            $collectionArray[$key] = $collectionInfo;
        }
      }

      if ($multimediaArray){
        $arrayMultimediaBlock = $this->defResCtrl->goOverCollections( $multimediaArray, $collectionType = 'multimedia' );
        if ($arrayMultimediaBlock){
          foreach ($arrayMultimediaBlock as $multimediaBlock){
            //$multimediaBlock->assign( 'max', 6 );
            //$multimediaBlock->setTpl('appEspazoNaturalMultimediaViewBlock.tpl', 'rtypeAppEspazoNatural');
            $template->addToFragment( 'multimediaGalleries', $multimediaBlock );
          }
        }
      }

      if ($collectionArray){
        $arrayCollectionBlock = $this->defResCtrl->goOverCollections( $collectionArray, $collectionType = 'base' );
        if ($arrayCollectionBlock){
          foreach ($arrayCollectionBlock as $collectionBlock){
            //$collectionBlock->setTpl('appEspazoNaturalCollectionViewBlock.tpl', 'rtypeAppEspazoNatural');
            $template->addToFragment( 'collections', $collectionBlock );
          }
        }
      }
    }

    $viewBlockInfo['template']['full'] = $template;

    return $viewBlockInfo;
  }

} // class RTypeAppEspazoNaturalController
