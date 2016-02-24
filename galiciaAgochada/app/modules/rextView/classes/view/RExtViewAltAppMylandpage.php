<?php


class RExtViewAltAppMylandpage {

  public $defRExtViewCtrl = false;
  public $defRTypeCtrl = false;
  public $defResCtrl = false;


  public function __construct( $defRExtViewCtrl ){
    //error_log( 'RExtViewAltAppMylandpage::__construct' );
    $this->defRExtViewCtrl = $defRExtViewCtrl;
    $this->defRTypeCtrl = $this->defRExtViewCtrl->defRTypeCtrl;
    $this->defResCtrl = $this->defRTypeCtrl->defResCtrl;
  }



  /**
    Alteramos la visualizacion el Recurso
   */
  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    //error_log( "RExtViewAltAppMylandpage: alterViewBlockInfo( viewBlockInfo, $templateName )" );
    /*
      $viewBlockInfo = array(
        'template' => array objTemplate,
        'data' => resourceData,
        'ext' => array rExt->viewBlockInfo,
        'header' => true, // true, false or html content
        'footer' => true  // true, false or html content
      );
    */

    // Podemos obtener datos de las estructura
    $resId = $viewBlockInfo['data']['id'];

    // Cambiar datos
    $viewBlockInfo['data']['title'] = 'Un dato metido desde o View Aternativo ;-) ('.$resId.')';

    // Recargamos los datos en las variables que usamos del objTemplate
    $viewBlockInfo['template']['full']->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );

    // Reemplazar el tpl "normal" de "full"
    $viewBlockInfo['template']['full']->setTpl( 'rExtViewAltAppMylandpage.tpl', 'rextView' );

    // Definir variables nuevas
    $viewBlockInfo['template']['full']->assign( 'altVar', 'Var creada en el alterView' );

    // Añadir estilos
    // $viewBlockInfo['template']['full']->addClientScript( 'js/rExtViewAltAppMylandpage.js', 'rextView' );
    $viewBlockInfo['template']['full']->addClientStyles( 'styles/rExtViewAltAppMylandpage.less', 'rextView' );

    //
    // O puede crearse una estructura $viewBlockInfo nueva desde cero
    //

    return $viewBlockInfo;
  }

} // class RExtViewAltAppMylandpage
