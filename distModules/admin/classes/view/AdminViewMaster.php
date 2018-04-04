<?php
Cogumelo::load('coreView/View.php');
common::autoIncludes();
geozzy::autoIncludes();
admin::autoIncludes();
form::autoIncludes();
user::autoIncludes();
table::autoIncludes();

form::loadDependence('ckeditor');
if( Cogumelo::getSetupValue('mod:geozzy:resource:htmlEditorBig') ) {
  form::loadDependence('grapesjs');
  form::loadDependence('grapesjsPresetWebpage');
}

if( class_exists( 'rtypeStory' ) ) {
  rtypeStory::autoIncludes();
}



class AdminViewMaster extends View {

  public function __construct( $base_dir = false ) {
    parent::__construct($base_dir);

    $this->langDefault = Cogumelo::getSetupValue( 'lang:default' );

    $langsConf = Cogumelo::getSetupValue( 'lang:available' );
    if( is_array( $langsConf ) ) {
      $this->langAvailable = array_keys( $langsConf );
    }

    if( class_exists( 'adminBI' ) ) {
      adminBI::autoIncludes();
    }
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();

    $res = true;
    if( !$useraccesscontrol->isLogged() ) {
      if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] ){
        header("HTTP/1.0 401");
      }
      else {
        header("HTTP/1.0 403");
        Cogumelo::redirect('/admin/login');
      }
      $res = false;
    }
    else {
      if( !$useraccesscontrol->checkPermissions( 'admin:access', 'admin:full' ) ) {
        $res = false;
        header("HTTP/1.0 401");
        // Cogumelo::redirect('/403/');
      }
    }


    return $res;
  }

  public function accessDenied(){
    $template = new Template( $this->baseDir );
    $template->setTpl('admin403.tpl', 'admin');

    $this->template->addToFragment( 'col12', $template );
    $this->template->assign( 'headTitle', __('Access denied') );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  public function homePage(){

    $defaultURL = Cogumelo::getSetupValue( 'mod:admin:defaultURL' );
    if($defaultURL){
      Cogumelo::redirect($defaultURL);
      exit;
    }

    $template = new Template( $this->baseDir );
    $template->setTpl('homePage.tpl', 'admin');

    $this->template->addToFragment( 'col12', $template );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }

  public function commonAdminInterface(){
    $this->template->setTpl('adminMaster.tpl', 'admin');
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();

    if (array_key_exists('avatar', $user['data'])){
      $fileCntrl = new FiledataModel();
      $avatar = $fileCntrl->listItems( [ 'filters' => ['id' => $user['data']['avatar'] ]] )->fetch();
      $user['data']['avatarAKey'] = $avatar->getter('aKey');
      $user['data']['avatarName'] = $avatar->getter('name');
    }
    $this->template->assign( 'user' , $user );

    //Control menu
    $superAdminPermission = $useraccesscontrol->checkPermissions();
    $this->template->assign( 'superAdminPermission' , $superAdminPermission);
    $userPermission = $useraccesscontrol->checkPermissions('user:all', 'admin:full');
    $this->template->assign( 'userPermission' , $userPermission);
    $topicPermission = $useraccesscontrol->checkPermissions('topic:list', 'admin:full');
    $this->template->assign( 'topicPermission' , $topicPermission);
    $pagePermission = $useraccesscontrol->checkPermissions('page:list', 'admin:full');
    $this->template->assign( 'pagePermission' , $pagePermission);
    $settingPermission = $useraccesscontrol->checkPermissions('setting:list', 'admin:full');
    $this->template->assign( 'settingPermission' , $settingPermission);

    if( class_exists( 'adminBI' ) ) {
      $this->template->assign( 'biInclude' , true);
    }
    if( class_exists( 'rextComment' ) ) {
      $this->template->assign( 'rextCommentInclude' , true);
    }
    if( class_exists( 'rextStoryStep' ) ) {
      $this->template->assign( 'rextStoryInclude' , true);
    }

    $logoPath = Cogumelo::getSetupValue( 'mod:admin:logoPath' );
    if($logoPath){
      $this->template->assign( 'logoCustom' , $logoPath);
    }

    $titleCustom = Cogumelo::getSetupValue( 'mod:admin:titlePath' );
    if($titleCustom){
      $this->template->assign( 'titleCustom' , $titleCustom);
    }

    $menuClosed = Cogumelo::getSetupValue( 'mod:admin:menuClosed' );
    if($menuClosed){
      $this->template->assign( 'menuClosed' , $menuClosed);
    }
    //
    $this->template->exec();
  }


  public function sendLogout() {
    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();
    Cogumelo::redirect('/admin');
  }


  public function extractFormBlockFields( $formBlock, $vars ) {
    $formFragment = array();

    $vars = is_array( $vars ) ? $vars : array( $vars );

    // Tomamos la variable del formulario principal con todos los campos
    $formFieldsArray = $formBlock->getTemplateVars( 'formFieldsArray' );
    $formFieldsHiddenArray = $formBlock->getTemplateVars( 'formFieldsHiddenArray' );

    foreach( $vars as $field ) {
      $langs = ( isset( $formFieldsArray[ $field ] ) ) ? array( '' ) : $this->langAvailable;
      foreach( $langs as $lang ) {
        $fieldLang = ( $lang !== '' ) ? $field.'_'.$lang : $field;
        if( isset( $formFieldsArray[ $fieldLang ] ) ) {
          $formFragment[ $fieldLang ] = $formFieldsArray[ $fieldLang ];
          //unset( $formFieldsArray[ $fieldLang ] );
          $formFieldsHiddenArray[] = $fieldLang;
        }
      }
    }

    // Guardamos la variable del formulario principal sin los campos extraidos
    //$formBlock->assign( 'formFieldsArray', $formFieldsArray );
    $formBlock->assign( 'formFieldsHiddenArray', $formFieldsHiddenArray );

    return ( count( $formFragment ) > 0 ) ? $formFragment : false;
  }


  public function getPanelBlock( $content, $title = '', $icon = false ) {
    $template = new Template( $this->baseDir );

    if( is_string( $content ) ) {
      $template->assign( 'content', $content );
    }
    else {
      $template->setFragment( 'content', $content );
    }
    $template->assign( 'title', $title );
    $template->assign( 'icon', ( $icon ) ? $icon : 'fa-info' );
    $template->setTpl( 'adminPanel.tpl', 'admin' );

    return $template;
  }

}
