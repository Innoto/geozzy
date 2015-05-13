<?php
require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');
geozzy::autoIncludes();
geozzy::load( 'view/GeozzyResourceView.php' );
//admin::autoIncludes();
user::autoIncludes();
common::autoIncludes();


/**
* Clase Master to extend other application methods
*/
class AdminFormsAPIView extends View
{

  public function __construct( $baseDir ){
    parent::__construct( $baseDir );
    $this->baseDir = $baseDir;
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {

    $useraccesscontrol = new UserAccessController();
    $res = true;

    if( !GEOZZY_API_ACTIVE || !$useraccesscontrol->isLogged() ){
      $res = false;
    }


    if( $res == false ) {
      header("HTTP/1.0 303");
      header('Content-type: application/json');
      echo '{}';
      exit;
    }

    return $res;

  }


  function categories() {
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));

    header('Content-type: application/json');

    echo '[';

    $c = '';
    while ($taxGroup = $taxGroupList->fetch() )
    {
      $taxData = $taxGroup->getAllData();
      echo $c.json_encode( $taxData['data'] );
      if($c === ''){$c=',';}
    }
    echo ']';

  }


  public function categoryForm( $request ){
    $geozzyTaxtermView = new GeozzyTaxonomytermView();

    $form = $geozzyTaxtermView->taxtermFormDefine( $request );
    $form->setAction('/api/admin/category/term/sendcategoryterm');
    $form->setSuccess( 'redirect', '/admin#category/'.$request[1] );

    $taxtermFormHtml = $geozzyTaxtermView->taxtermFormGet( $form );

    $this->template->assign('taxtermFormHtml', $taxtermFormHtml);
    $this->template->setTpl('taxtermForm.tpl', 'admin');

    $this->template->exec();
  }

  public function sendCategoryForm(){
    $geozzyTaxtermView = new GeozzyTaxonomytermView();
    $geozzyTaxtermView->sendTaxtermForm();
  }



  /**
    Creacion/Edicion de Recursos
  */

  public function resourceForm() {
    error_log( "AdminFormsAPIView: resourceForm()" );

    $formName = 'resourceCreate';
    $formUrl = '/api/admin/resource/sendresource';

    $resourceView = new GeozzyResourceView();
    $formBlock = $resourceView->getFormBlock( $formName,  $formUrl, false );
    $panel = $this->getPanelBlock( $formBlock, 'New Resource', 'fa-archive' );
    $this->template->addToBlock( 'col8', $panel );


    $panel = $this->getPanelBlock( 'Recuerda que en algunos campos existe versión en varios idiomas.' );
    $this->template->addToBlock( 'col4', $panel );

    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

    $this->template->exec();
  } // function resourceForm()


  public function resourceEditForm( $urlParams = false ) {
    error_log( "AdminFormsAPIView: resourceEditForm()". print_r( $urlParams, true ) );

    $formName = 'resourceCreate';
    $formUrl = '/api/admin/resource/sendresource';

    $recurso = false;

    if( isset( $urlParams['1'] ) ) {
      $idResource = $urlParams['1'];
      $recModel = new ResourceModel();
      $recursosList = $recModel->listItems( array( 'affectsDependences' => array( 'FiledataModel' ),
        'filters' => array( 'id' => $idResource ) ) );
      $recurso = $recursosList->fetch();
    }

    if( $recurso ) {
      $recursoData = $recurso->getAllData();

      $resourceView = new GeozzyResourceView();
      $formBlock = $resourceView->getFormBlock( $formName,  $formUrl, $recursoData[ 'data' ] );
      $panel = $this->getPanelBlock( $formBlock, 'Edit Resource', 'fa-archive' );
      $this->template->addToBlock( 'col8', $panel );

      $html = 'Recurso asociado con:<br>'.
        ' <i class="fa fa-times"></i> Playas<br>'.
        ' <i class="fa fa-times"></i> Lugares<br>'.
        ' <i class="fa fa-times"></i> Fiesta<br>'.
        '<br>'.
        ' <i class="fa fa-times"></i> Desvincular de TODAS<br>'.
        '<br>'.
        ' <i class="fa fa-times"></i> Eliminar Recurso<br>'.
        '';
      $panel = $this->getPanelBlock( $html, 'Information' );
      $this->template->addToBlock( 'col4', $panel );

      $this->template->addToBlock( 'col4', $this->getPanelBlock( 'Esto é un segundo panel' ) );

      $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );

      $this->template->exec();
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function resourceEditForm()


  public function sendResourceForm() {
    error_log( "AdminFormsAPIView: sendResourceForm()" );

    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  } // sendResourceForm()

  /**
    Creacion/Edicion de Recursos - FIN
  */


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
