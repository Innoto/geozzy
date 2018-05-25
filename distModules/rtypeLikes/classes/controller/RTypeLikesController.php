<?php

class RTypeLikesController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeLikes() );
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


    // Cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam( 'topics', 'type', 'reserved' );
    $form->setFieldParam( 'starred', 'type', 'reserved' );
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');
    $form->removeField( 'externalUrl' );
    $form->removeField( $form->multilangFieldNames( 'urlAlias' ) );
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

    // TEMPLATE panel principa del form. Contiene los elementos globales del form.
    $templates['formBase'] = new Template();
    $templates['formBase']->setTpl( 'rTypeFormBase.tpl', 'geozzy' );
    $templates['formBase']->assign( 'title', __('Main Resource information') );
    $templates['formBase']->assign( 'res', $formBlockInfo );

    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'title' ),
      $form->multilangFieldNames( 'shortDescription' ),
      $form->multilangFieldNames( 'content' )
    );
    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );


    $likesTemplate = false;
    if( $formBlockInfo['data']['id'] ) {
      $likesData = $this->getLikesData( $formBlockInfo['data']['id'] );

      if( $likesData ) {
        // TEMPLATE panel estado de publicacion
        $likesTemplate = new Template();
        $likesTemplate->setTpl( 'likeAdminFormPanel.tpl', 'rtypeLikes' );
        $likesTemplate->assign( 'title', __( 'Likes' ) );
        $likesTemplate->assign( 'res', $formBlockInfo );
        $likesTemplate->assign( 'likesData', $likesData );
      }
    }


    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );

    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    if( $likesTemplate ) {
      $templates['adminFull']->addToFragment( 'col8', $likesTemplate );
    }
    // $templates['adminFull']->addToFragment( 'col8', $templates['likeResources'] );
    // COL4
    $templates['adminFull']->addToFragment( 'col4', $templates['publication'] );
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
  public function getViewBlockInfo( $resId = false ) {
    $viewBlockInfo = false;

    $userAccessCtrl = new UserAccessController();
    $userInfo = $userAccessCtrl->getSessiondata();

    $resUser = $this->defResCtrl->resObj->getter('user');

    if( $userInfo && $userInfo['data']['id'] === $resUser ) {
      // Preparamos los datos para visualizar el Recurso con sus extensiones
      $viewBlockInfo = parent::getViewBlockInfo( $resId );

      $viewBlockInfo['data']['title'] = __("Likes");

      // $template = new Template();
      $template = $viewBlockInfo['template']['full'];
      $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeLikes' );

      $template->addClientScript( 'js/rExtLikeController.js', 'rextLike' );

      $likesResources = $this->getLikesResources( $viewBlockInfo['data']['id'] );
      $likesResourcesInfo = ($likesResources) ? $this->getResourcesInfo( $likesResources ) : false;
      $template->assign( 'likesResourcesInfo', $likesResourcesInfo );


      // $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );
      $viewBlockInfo['template']['full'] = $template;
    }

    return $viewBlockInfo;
  }


  /**
   * UTILS
   */
  public function getLikesData( $likesId ) {
    $likesData = false;

    $likeModel = new LikesViewModel();
    $likeList = $likeModel->listItems( array( 'filters' => array( 'resourceMain' => $likesId ) ) );
    if( $likeList ) {
      $likesData = array();
      while( $likeObj = $likeList->fetch() ) {
        $allData = $likeObj->getAllData( 'onlydata' );
        if( isset( $allData['id'] ) && $allData['id'] !== null ) { // Por si hay col. pero no recursos
          $likesData[] = $allData;
        }
      }
    }

    return $likesData;
  }

  public function getLikesResources( $likesId ) {
    $likesResources = false;

    $likeModel = new LikesListViewModel();
    $likeList = $likeModel->listItems( array( 'filters' => array( 'id' => $likesId, 'resourceListNotNull' => true ) ) );
    $likeObj = ( $likeList ) ? $likeList->fetch() : false;
    $likeData = ( $likeObj ) ? $likeObj->getAllData( 'onlydata' ) : false;
    $likesResources = ( isset( $likeData['resourceList'] ) ) ? explode( ',', $likeData['resourceList'] ) : false;

    return $likesResources;
  }

  public function getResourcesInfo( $resIds ) {
    geozzy::load('model/ResourceModel.php');
    $resInfo = array();

    $perfilLikeImg = empty( Cogumelo::getSetupValue( 'mod:filedata:profile:likePageImg' ) ) ?  'wmdpi4' : 'likePageImg';

    $resourceModel = new ResourceViewModel();
    $resourceList = $resourceModel->listItems( array( 'filters' => array( 'inId' => $resIds, 'published' => 1 ) ) );
    while( $resObj = $resourceList->fetch() ) {
      $resId = $resObj->getter('id');
      $resInfo[ $resId ] = array(
        'id' => $resObj->getter('id'),
        'title' => $resObj->getter('title'),
        'shortDescription' => $resObj->getter('shortDescription'),
        'image' => $resObj->getter('image'),
        'imageAKey' => $resObj->getter('imageAKey'),
        'imageName' => $resObj->getter('imageName'),
        'perfilLikeImg' => $perfilLikeImg,
        'url' => $resObj->getter('urlAlias'),
        'rTypeId' => $resObj->getter('rTypeId')
      );
    }

    return $resInfo;
  }



} // class RTypeBlogController
