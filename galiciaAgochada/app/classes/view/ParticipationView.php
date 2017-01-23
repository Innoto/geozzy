<?php
Cogumelo::load('view/MasterView.php');
geozzy::autoIncludes();
geozzy::load( 'view/GeozzyResourceView.php' );
form::autoIncludes();
form::loadDependence( 'ckeditor' );


class ParticipationView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  public function xantaresForm() {
    global $C_LANG;
    $htmlModalOk = '<h2>'. __("¡Moitas grazas!").'</h2>';
    $htmlModalOk .= '<p>'. __("Revisaremos a túa achega. Se nos gusta publicarémola.").'</p>';
    $htmlModalOk .= '<p>'. __("Manterémoste informad@ por e-mail.").'</p>';

    $successArray = false;
    $successArray[ 'jsEval' ] = 'new geozzy.generateModal({ classCss: "xantaresParticipationOk", htmlBody:"'.$htmlModalOk.'" , successCallback: geozzy.xantaresParticipationForm.closeModal()});';

    $recursoData = $this->xantaresCommonForm();

    $resCtrl = new ResourceController();
    $formBlockInfo = $resCtrl->getFormBlockInfo( "participationXantaresForm", "/".$C_LANG."/participation/xantaresExplorer/send", $successArray, $recursoData );
    //$formBlockInfo['objForm']->saveToSession();

    $formBlockInfo['template']['participationFull']->exec();

  }

  public function xantaresWebViewForm() {
    global $C_LANG;

    $successArray = false;
    $successArray[ 'jsEval' ] = 'GeozzyMobileApp.resultParticipation(true);';

    $recursoData = $this->xantaresCommonForm();

    $resCtrl = new ResourceController();
    $formBlockInfo = $resCtrl->getFormBlockInfo( "participationXantaresForm", "/".$C_LANG."/participation/xantaresExplorer/send", $successArray, $recursoData );

    $formBlockInfo['template']['participationWV']->addClientStyles( 'styles/masterWV.less' );
    $formBlockInfo['template']['participationWV']->exec();

  }

  public function xantaresCommonForm(){
    $rTypeItem = false;
    $rtypeControl = new ResourcetypeModel();
    $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'idName' => 'rtypeAppRestaurant' ) ) )->fetch();
    $recursoData['rTypeId'] = $rTypeItem->getter('id');
    $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');
    $topicControl = new TopicModel();
    $topicItem = $topicControl->ListItems( array( 'filters' => array( 'idName' => 'participation' ) ) )->fetch();
    $recursoData['topics'] = array($topicItem->getter('id'));

    if( isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['zoom']) ){
      $recursoData['locLat'] = $_POST['lat'];
      $recursoData['locLon'] = $_POST['lng'];
      $recursoData['defaultZoom'] = $_POST['zoom'];
    }
    return $recursoData;
  }

  public function sendXantaresForm() {
    $resourceView = new GeozzyResourceView();
    $resource = null;
    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $resourceView->defResCtrl->resFormLoad();
    if( !$form->existErrors() ) {
      // Validar y guardar los datos
      $resource = $resourceView->actionResourceFormProcess( $form );
    }
    // Enviamos el OK-ERROR a la BBDD y al formulario
    $resourceView->actionResourceFormSuccess( $form, $resource );
  }

}
