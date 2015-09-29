<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
geozzy::autoIncludes();
admin::autoIncludes();
form::autoIncludes();
form::loadDependence( 'ckeditor' );
user::autoIncludes();
table::autoIncludes();
bi::autoIncludes();


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
      if( $_SERVER['HTTP_X_REQUESTED_WITH'] ){
        header("HTTP/1.0 401");
      }
      else {
        Cogumelo::redirect('/admin/login');
      }
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
    $formFieldsHiddenArray = $formBlock->getTemplateVars( 'formFieldsHiddenArray' );

    global $LANG_AVAILABLE;

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
      $template->setBlock( 'content', $content );
    }
    $template->assign( 'title', $title );
    $template->assign( 'icon', ( $icon ) ? $icon : 'fa-info' );
    $template->setTpl( 'adminPanel.tpl', 'admin' );

    return $template;
  }

}
