<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
geozzy::autoIncludes();
form::autoIncludes();
form::loadDependence( 'ckeditor' );

class CommentView extends View {

  public function __construct( $base_dir ) {
    parent::__construct($base_dir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck(){
    return true;
  }

  public function commentForm( $param ) {

    $validation = array(
      'resource' => '#(\d+)#',
      'commenttype'=> '#(.*)#'
    );
    $extraParams = RequestController::processUrlParams( $param, $validation );

    if( !array_key_exists('resource', $extraParams ) ){
      exit();
    }
    $resourceID = $extraParams['resource'];
    $ctype = array_key_exists( 'commenttype', $extraParams) ? $extraParams['commenttype'] : false;

    $commentCtrl = new RExtCommentController();
    $permissionsComment = $commentCtrl->getCommentPermissions($resourceID);
    $publishComment = $commentCtrl->commentPublish($resourceID);

    $ctypeParamTerms = $permissionsComment;
    if( $ctype ) {
      $ctypeParamTerms = ( in_array( $ctype, $permissionsComment ) ? array( $ctype ) : $permissionsComment );
    }

    $commentTypeTerms = $commentCtrl->getCommentTypeTerms( $ctypeParamTerms );

    $commentTypeDefault = false;
    $commentTypeTermsArray = array();
    foreach( $commentTypeTerms as $termId => $termIdName ) {
      if( $commentTypeDefault === false ) {
        $commentTypeDefault = $termId;
      }
      if( $termIdName === 'comment') {
        $commentTermID = $termId;
        $commentTypeTermsArray[ $termId ] = __('A public comment and evaluation');
      }
      elseif( $termIdName === 'suggest' ) {
        $suggestTermID = $termId;
        $commentTypeTermsArray[ $termId ] = __('A private suggestion about this content');
      }
    }


    // Data Options Suggest Type
    $suggestTypeTerms = $commentCtrl->getSuggestTypeTerms();
    $suggestTypeTermsArray = array();
    foreach( $suggestTypeTerms as $term ) {
      $suggestTypeTermsArray[ $term['id'] ] = $term['name'];
    }

    $commentRatePerm = $commentCtrl->getCommentRate( $resourceID );

    // If exist user session
    $useraccesscontrol = new UserAccessController();
    $userSess = $useraccesscontrol->getSessiondata();
    $userID = ($userSess) ? $userSess['data']['id'] : null;

    $form = new FormController('commentForm'); //actionform
    $form->setAction( '/comment/sendcommentform' );
    $form->setSuccess( 'jsEval', 'geozzy.commentInstance.successCommentBox('.$resourceID.');' );

    $fieldsInfo = array();
    $fieldsInfo['id'] = array(
      'params' => array( 'type' => 'reserved', 'value' => null )
    );

    if( count($commentTypeTermsArray) > 1 ) {
      $fieldsInfo['type'] = array(
        'params' => array( 'type' => 'radio', 'label' => __('What do you contribute?'), 'value' => $commentTypeDefault,
          'options'=> $commentTypeTermsArray
        ),
        'rules' => array( 'required' => true )
      );
    }
    else {
      $fieldsInfo['type'] = array(
        'params' => array( 'type' => 'reserved', 'value' => $commentTypeDefault )
      );
    }

    if( !$userID ) {
      $fieldsInfo['anonymousName'] = array(
        'params' => array( 'label' => __('Your name') ),
        'rules' => array( 'required' => true )
      );
      $fieldsInfo['anonymousEmail'] = array(
        'params' => array( 'label' => __('Your email') ),
        'rules' => array( 'required' => true, 'email' => true )
      );
    }

    if(in_array('comment', $ctypeParamTerms) && $commentRatePerm){
      $fieldsInfo['rate'] = array(
        'params' => array( 'label' => __('Rate this content'), 'class' => 'inputRating', 'value' => null )
      );
    }

    if(in_array('suggest', $ctypeParamTerms)){
      $fieldsInfo['suggestType'] = array(
        'params' => array( 'label' => __( 'What do we suggest?' ), 'type' => 'select',
          'options'=> $suggestTypeTermsArray
        )
      );
    }

    $fieldsInfo['content'] = array(
      'params' => array( 'label' => __( 'Your comment' ), 'type' => 'textarea' ),
      'rules' => array( 'required' => true )
    );
    $fieldsInfo['published'] = array(
      'params' => array( 'type' => 'reserved', 'value' => $publishComment )
    );
    $fieldsInfo['user'] = array(
      'params' => array( 'type' => 'reserved', 'value' => $userID )
    );
    $fieldsInfo['resource'] = array(
      'params' => array( 'type' => 'reserved', 'value' => $resourceID )
    );
    $fieldsInfo['submit'] = array(
      'params' => array( 'type' => 'submit', 'value' => __('Send') )
    );

    $form->definitionsToForm( $fieldsInfo );
    if(!$userID){
      $form->setValidationRule( 'anonymousEmail', 'email' );
    }
    $form->saveToSession();

    $template = new Template( $this->baseDir );
    $template->addClientScript("js/commentForm.js", 'rextComment');
    $template->assign("commentFormOpen", $form->getHtmpOpen());
    $template->assign("commentFormFields", $form->getHtmlFieldsArray());
    $template->assign("commentFormClose", $form->getHtmlClose());
    $template->assign("commentFormValidations", $form->getScriptCode());

    if( count($commentTypeTermsArray) > 1){
      $commentCustomScript = '<script> var suggestTermID = '.$suggestTermID.';  var commentTermID = '.$commentTermID.'; </script>';
    }
    else {
      $commentCustomScript = '<script> var suggestTermID = false;  var commentTermID = false; </script>';
    }

    $template->assign("commentCustomScript", $commentCustomScript);
    $template->setTpl('comment.tpl', 'rextComment');
    $template->exec();
  }


  public function sendCommentForm() {
    $form = $this->actionCommentForm();
    $this->commentOk( $form );
    $form->sendJsonResponse();
  }

  public function actionCommentForm() {
    $form = new FormController();
    if( $form->loadPostInput() ) {
      $form->validateForm();
    }
    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();
      if(!$valuesArray['resource']){
        $form->addFormError(__('An unexpected error has occurred'));
      }
      else {
        $commentCtrl = new RExtCommentController();
        $permissionsInfo = $commentCtrl->getPermissions( $valuesArray['resource'] );
        $permissions = isset( $permissionsInfo[ $valuesArray['resource'] ] ) ? $permissionsInfo[ $valuesArray['resource'] ] : false;

        if( empty($valuesArray['user']) && empty($permissions['anonymous']) ){
          Cogumelo::error("Comentarios anónimos no permitidos Resource:".$valuesArray['resource']);
          exit;
        }

        //TODO: Comprobar que el recurso tiene comentarios o sugerencias activadas en Conf y en BBDD
      }
    }

    return $form;
  }

  public function commentOk( $form ) {
    $comment = false;
    $notificarComentario = false;
    //Si tod0 esta OK!
    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();

      $valuesArray['timeCreation'] = date("Y-m-d H:i:s", time());

      if(!empty($valuesArray['rate']) && $valuesArray['rate']!== 0){
        $valuesArray['rate'] = $valuesArray['rate'] * 20;
      }else{
        $valuesArray['rate'] = null;
      }
      $comment = new CommentModel( $valuesArray );
      $comment->save();

      $resControl = new ResourceViewModel();
      $res = $resControl->listItems( ['filters' => [ 'id' => $valuesArray['resource'] ]] )->fetch();
      $resData = $res->getAllData('onlydata');

      $userCommentControl = new UserCommentModel();
      $userCommentList = $userCommentControl->listItems(['filters' => ['user' => $resData['user']  ]]);

      $userCommentObj = is_object( $userCommentList ) ? $userCommentList->fetch() : false;
      $userComment = is_object( $userCommentObj ) ? $userCommentObj->getAllData( 'onlydata' ) : false;


      $confNotify = Cogumelo::getSetupValue('mod:geozzy:resource:commentRules:default:notify');
      if( $userComment === false ){
        if(!empty($confNotify)){
          $notificarComentario = true;
        }
      }
      else{
        if( !empty($userComment['notify']) ){
          $notificarComentario = true;
        }
      }

      if($notificarComentario){
        Cogumelo::load( 'coreController/MailController.php' );
        $mailCtrl = new MailController();
        $bodyPlain = new Template();
        $bodyHtml = new Template();
        $bodyPlain->setTpl( 'notificationCommentMailPlain.tpl', 'rextComment');
        $bodyHtml->setTpl( 'notificationCommentMailHtml.tpl', 'rextComment' );

        $useraccesscontrol = new UserAccessController();
        $user = $useraccesscontrol->getSessiondata();
        $adresses = $resData['userEmail'];

        $vars = array(
          'userName' => $user['data']['name'],
          'userSurname' => $user['data']['surname'],
          'userLogin' => $user['data']['login'],
          'res_title' => $res->getter('title')
        );

        $mailCtrl->setBody( $bodyPlain, $bodyHtml, $vars );
        $mailCtrl->send( $adresses, __('You have received a comment') );
      }

      // Consultamos el valor de valoracion media y lo guardamos en el recurso
      // $averageVotesModel = new AverageVotesViewModel();
      // $resAverageVotes = $averageVotesModel->listItems( array('filters' => array('id' => $comment->getter('resource')) ))->fetch();
    }

    return $comment;
  }

}
