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

  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {

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
    //$form->setSuccess( 'redirect', '/api/admin/categories' );

    $taxtermFormHtml = $geozzyTaxtermView->taxtermFormGet( $form );

    $this->template->assign('taxtermFormHtml', $taxtermFormHtml);
    $this->template->setTpl('taxtermForm.tpl', 'admin');

    $this->template->exec();
  }

  public function sendCategoryForm(){
    $geozzyTaxtermView = new GeozzyTaxonomytermView();
    $geozzyTaxtermView->sendTaxtermForm();
  }




  public function resourceForm() {

    $formName = 'resourceCreate';
    $formUrl = '/api/admin/resource/sendresource';

    error_log( "RecursoView: crearForm()" );

    $resourceView = new GeozzyResourceView();
    $formBlock = $resourceView->getFormBlock( $formName,  $formUrl, false );
    $this->template->setBlock( 'formNewResourceBlock', $formBlock );
    $this->template->setTpl( 'string:{$css_includes}{$js_includes}{$formNewResourceBlock}' );
    $this->template->exec();
  } // function resourceForm()


  public function resourceEditForm( $urlParams = false ) {


    $formName = 'resourceCreate';
    $formUrl = '/api/admin/resource/sendresource';


    error_log( "RecursoView: editarForm()". print_r( $urlParams, true ) );

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
      $this->template->setBlock( 'formNewResourceBlock', $formBlock );
      $this->template->setTpl( 'string:{$css_includes}{$js_includes}{$formNewResourceBlock}' );
      $this->template->exec();
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function resourceEditForm()


  public function sendResourceForm() {
    error_log( "RecursoView: actionResourceForm()" );

    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  } // actionResourceForm()




}
