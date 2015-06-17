<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
geozzy::autoIncludes();
admin::autoIncludes();
form::autoIncludes();
form::loadDependence( 'ckeditor' );
user::autoIncludes();
table::autoIncludes();


class AdminViewMaster extends View
{

  public function __construct( $base_dir ) {
    parent::__construct($base_dir);

    $this->langDefault = LANG_DEFAULT;
    global $LANG_AVAILABLE;
    if( isset( $LANG_AVAILABLE ) && is_array( $LANG_AVAILABLE ) ) {
      $this->langAvailable = array_keys( $LANG_AVAILABLE );
    }
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    $useraccesscontrol = new UserAccessController();
    $res = true;
    if(!$useraccesscontrol->isLogged()){
      header("HTTP/1.0 401");
      Cogumelo::redirect('/admin/login');
      $res = false;
    }
    return $res;
  }


  public function commonAdminInterface(){
    $this->template->setTpl('adminMaster.tpl', 'admin');
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();
    $this->template->assign( 'user' , $user);
    $this->template->exec();
  }


  public function sendLogout() {
    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();
    Cogumelo::redirect('/admin');
  }


  public function extractFormBlockFields( $formBlock, $vars ) {
    $formFragment = array();

    // Tomamos la variable del formulario principal con todos los campos
    $formFieldsArray = $formBlock->getTemplateVars( 'formFieldsArray' );
    global $LANG_AVAILABLE;

    foreach( $vars as $field ) {
      $langs = ( isset( $formFieldsArray[ $field ] ) ) ? array( '' ) : $this->langAvailable;
      foreach( $langs as $lang ) {
        $fieldLang = ( $lang !== '' ) ? $field.'_'.$lang : $field;
        $formFragment[ $fieldLang ] = $formFieldsArray[ $fieldLang ];
        unset( $formFieldsArray[ $fieldLang ] );
      }
    }

    // Guardamos la variable del formulario principal sin los campos extraidos
    $formBlock->assign( 'formFieldsArray', $formFieldsArray );

    return $formFragment;
  }


  public function getPanelBlock( $content, $title = '', $icon = 'fa-info' ) {
    $template = new Template( $this->baseDir );

    if( is_string( $content ) ) {
      $template->assign( 'content', $content );
    }
    else {
      $template->setBlock( 'content', $content );
    }
    $template->assign( 'title', $title );
    $template->assign( 'icon', $icon );
    $template->setTpl( 'adminPanel.tpl', 'admin' );

    return $template;
  }

}
