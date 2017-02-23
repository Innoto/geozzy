<?php

class RTypeCommunityController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeCommunity() );

    $this->rExtCommCtrl = new RExtCommunityController();
  }


  /**
   * Alteramos el objeto form. del recursoBase para adaptarlo a las necesidades del RType
   *
   * @param $form FormController Objeto form. del recursoBase
   *
   * @return array $rTypeFieldNames
   */
  public function manipulateForm( FormController $form ) {
  } // function manipulateForm()


  /**
   * Preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    return false;
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


      // TODO: TEMPORAL!!! Se calculan las afinidades en cada acceso
      rextCommunity::load('view/RExtCommunityAffinityView.php');
      $afinCtrl = new RExtCommunityAffinityView();
      $afinCtrl->updateAffinityModel();


      // Preparamos los datos para visualizar el Recurso con sus extensiones
      $viewBlockInfo = parent::getViewBlockInfo( $resId );

      // $template = new Template();
      $template = $viewBlockInfo['template']['full'];
      $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeCommunity' );

      $template->addClientScript( 'js/rExtCommunityController.js', 'rextCommunity' );

      $myInfo = $this->rExtCommCtrl->getUsersInfo( $viewBlockInfo['data']['user'] )[ $viewBlockInfo['data']['user'] ];
      $template->assign( 'myInfo', $myInfo );

      $commFollows = $this->rExtCommCtrl->getCommFollows( $viewBlockInfo['data']['user'] );
      $commFollowsInfo = ( $commFollows ) ? $this->rExtCommCtrl->getUsersInfo( $commFollows, $getFavs = true ) : false;
      $template->assign( 'commFollowsInfo', $commFollowsInfo );

      $commProposeList = $this->rExtCommCtrl->getCommPropose( $viewBlockInfo['data']['user'], $commFollows );
      $commProposeInfo = ( $commProposeList ) ? $this->rExtCommCtrl->getUsersInfo( $commProposeList, $getFavs = true ) : false;
      $template->assign( 'commProposeList', $commProposeList );
      $template->assign( 'commProposeInfo', $commProposeInfo );

      // $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );
      $viewBlockInfo['template']['full'] = $template;
    }

    return $viewBlockInfo;
  }

} // class RTypeBlogController
