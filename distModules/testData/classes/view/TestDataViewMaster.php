<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
geozzy::autoIncludes();



class TestDataViewMaster extends View
{

  public function __construct( $base_dir ) {
    parent::__construct($base_dir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    return true;
  }

  public function commonTestDataInterface(){
    $this->template->setTpl('testDataMaster.tpl', 'testData');
    $this->template->exec();
  }

  public function getPanelBlock( $content, $title = '', $icon = 'fa-info' ) {
    $template = new Template( $this->baseDir );

    if( is_string( $content ) ) {
      $template->assign( 'content', $content );
    }
    else {
      $template->setFragment( 'content', $content );
    }
    $template->assign( 'title', $title );
    $template->assign( 'icon', $icon );
    $template->setTpl( 'testDataPanel.tpl', 'testData' );
    xdebug_var_dump(xdebug.var_display_max_data);
    return $template;
  }

}
