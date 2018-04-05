<?php

class RTypeFavouritesController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeFavourites() );
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


    $favsTemplate = false;
    if( $formBlockInfo['data']['id'] ) {
      $favsData = $this->getFavsData( $formBlockInfo['data']['id'] );

      if( $favsData ) {
        // TEMPLATE panel estado de publicacion
        $favsTemplate = new Template();
        $favsTemplate->setTpl( 'favAdminFormPanel.tpl', 'rtypeFavourites' );
        $favsTemplate->assign( 'title', __( 'Favourites' ) );
        $favsTemplate->assign( 'res', $formBlockInfo );
        $favsTemplate->assign( 'favsData', $favsData );
      }
    }


    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );

    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    if( $favsTemplate ) {
      $templates['adminFull']->addToFragment( 'col8', $favsTemplate );
    }
    // $templates['adminFull']->addToFragment( 'col8', $templates['favResources'] );
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

      $viewBlockInfo['data']['title'] = __("Favourites");

      // $template = new Template();
      $template = $viewBlockInfo['template']['full'];
      $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeFavourites' );

      $template->addClientScript( 'js/rExtFavouriteController.js', 'rextFavourite' );

      $favsResources = $this->getFavsResources( $viewBlockInfo['data']['id'] );
      $favsResourcesInfo = ($favsResources) ? $this->getResourcesInfo( $favsResources ) : false;
      $template->assign( 'favsResourcesInfo', $favsResourcesInfo );


      // $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );
      $viewBlockInfo['template']['full'] = $template;
    }

    return $viewBlockInfo;
  }


  /**
   * UTILS
   */
  public function getFavsData( $favsId ) {
    $favsData = false;

    $favModel = new FavouritesViewModel();
    $favList = $favModel->listItems( array( 'filters' => array( 'resourceMain' => $favsId ) ) );
    if( $favList ) {
      $favsData = array();
      while( $favObj = $favList->fetch() ) {
        $allData = $favObj->getAllData( 'onlydata' );
        if( isset( $allData['id'] ) && $allData['id'] !== null ) { // Por si hay col. pero no recursos
          $favsData[] = $allData;
        }
      }
    }

    return $favsData;
  }

  public function getFavsResources( $favsId ) {
    $favsResources = false;

    $favModel = new FavouritesListViewModel();
    $favList = $favModel->listItems( array( 'filters' => array( 'id' => $favsId, 'resourceListNotNull' => true ) ) );
    $favObj = ( $favList ) ? $favList->fetch() : false;
    $favData = ( $favObj ) ? $favObj->getAllData( 'onlydata' ) : false;
    $favsResources = ( isset( $favData['resourceList'] ) ) ? explode( ',', $favData['resourceList'] ) : false;

    return $favsResources;
  }

  public function getResourcesInfo( $resIds ) {
    geozzy::load('model/ResourceModel.php');
    $resInfo = array();

    $perfilFavouriteImg = empty( Cogumelo::getSetupValue( 'mod:filedata:profile:favouritePageImg' ) ) ?  'wmdpi4' : 'favouritePageImg';

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
        'perfilFavouriteImg' => $perfilFavouriteImg,
        'url' => $resObj->getter('urlAlias'),
        'rTypeId' => $resObj->getter('rTypeId')
      );
    }

    return $resInfo;
  }



} // class RTypeBlogController
