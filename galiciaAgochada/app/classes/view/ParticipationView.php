<?php
Cogumelo::load('view/MasterView.php');


class ParticipationView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  public function xantaresForm() {
    $successArray = false;
    //$successArray[ 'jsEval' ] = 'geozzy.userSessionInstance.userRouter.successProfileForm();';
    $rTypeItem = false;
    $rtypeControl = new ResourcetypeModel();
    $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'idName' => 'rtypeAppRestaurant' ) ) )->fetch();
    $recursoData['rTypeId'] = $rTypeItem->getter('id');
    $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');

    $resCtrl = new ResourceController();
    $formBlockInfo = $resCtrl->getFormBlockInfo( "participationXantaresForm", "/participation/xantaresExplorer/send", $successArray, $recursoData );
    //$formBlockInfo['objForm']->saveToSession();

    $formBlockInfo['template']['participationFull']->exec();

  }

  public function sendXantaresForm() {
    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  }
}
