<?php
Cogumelo::load('view/MasterView.php');
geozzy::load( 'view/UrlAliasView.php' );

common::autoIncludes();
geozzy::autoIncludes();



class AliasView extends MasterView
{

  private $formName = 'aliasCreate';
  private $formUrl = '/alias-form-action';

  public function __construct( $baseDir ) {

    parent::__construct( $baseDir );
  }


  /**
    Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    return true;
  }


  /**
    Defino y muestro un formulario de creacion
  */
  public function crearForm() {
    error_log( "AliasView: crearForm()" );

    $aliasView = new UrlAliasView();
    $formBlock = $aliasView->getFormBlock( $this->formName,  $this->formUrl, false );
    $this->template->setBlock( 'formNewResourceBlock', $formBlock );

    $this->template->setTpl( 'probandoFormRecurso.tpl' );
    //$this->template->setTpl( 'string:{$css_includes}{$js_includes}{$formNewResourceBlock}' );
    $this->template->exec();
  } // function crearForm()


  /**
    Defino y muestro un formulario de edicion
  */
  public function editarForm( $urlParams = false ) {
    error_log( "AliasView: editarForm()". print_r( $urlParams, true ) );

    $elem = false;

    if( isset( $urlParams['1'] ) ) {
      $elemId = $urlParams['1'];
      $elemModel = new UrlAliasModel();
      $elemsList = $elemModel->listItems( array( 'filters' => array( 'id' => $elemId ) ) );
      $elem = $elemsList->fetch();
    }

    if( $elem ) {
      $elemData = $elem->getAllData();

      $elemView = new UrlAliasView();
      $formBlock = $elemView->getFormBlock( $this->formName,  $this->formUrl, $elemData[ 'data' ] );
      $this->template->setBlock( 'formNewResourceBlock', $formBlock );

      $this->template->setTpl( 'probandoFormRecurso.tpl' );
      $this->template->exec();
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function editarForm()



  /**
    Visualizamos el Recurso
  */
  public function mostrar( $urlParams = false ) {
    error_log( "AliasView: mostrar()" . print_r( $urlParams, true ) );

    $elemId = false;
    $html = '';

    $elemModel = new UrlAliasModel();
    if( isset( $urlParams['1'] ) ) {
      $elemId = $urlParams['1'];
      $elemList = $elemModel->listItems( array( 'filters' => array( 'id' => $elemId ) ) );
    }
    else {
      $elemList = $elemModel->listItems( array( 'order' => array( 'id' => 1 ) ) );
    }

    while( $elem = $elemList->fetch() ) {
      $allData = $elem->getAllData();
      $allData['data']['canonical'] = $allData['data']['canonical'] ? 'true' : 'false';
      $html .= "\n<pre>\n" . print_r( $allData['data'], true ) . "\n</pre>\n";
    }

    if( $html !== '' ) {
      echo $html;
    }
    else {
      echo 'NON HAI';
    }
  } // function showRecurso()



  /**
    Proceso formulario crear/editar Recurso
  */
  public function actionForm() {
    error_log( "AliasView: actionForm()" );

    $aliasView = new UrlAliasView();
    $aliasView->actionForm();
  } // actionForm()


} // class showRecurso
