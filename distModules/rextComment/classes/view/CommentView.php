<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
geozzy::autoIncludes();
form::autoIncludes();
form::loadDependence( 'ckeditor' );

class CommentView extends View
{

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

    $rextCommentControl = new RExtCommentController();
    $permissionsComment = $rextCommentControl->getCommentPermissions($resourceID);
    $publishComment = $rextCommentControl->commentPublish($resourceID);

    $ctypeParamTerms = $permissionsComment;
    if($ctype){
      $ctypeParamTerms = ( in_array($ctype, $permissionsComment) ? array($ctype) : $permissionsComment );
    }

    $taxModelControl = new TaxonomygroupModel();
    $termModelControl = new TaxonomytermModel();
    // Data Options Comment Type
    $commentTypeTax = $taxModelControl->listItems( array('filters' => array('idName' => 'commentType')) )->fetch();
    $commentTypeTerms = $termModelControl->listItems(
      array('filters' =>
        array(
          'taxgroup' => $commentTypeTax->getter('id'),
          'idNames' => $ctypeParamTerms
        )
      )
    )->fetchAll();
    $commentTypeTermsArray = array();
    $firstTermCount = 1;
    foreach ($commentTypeTerms as $term) {
      if($firstTermCount){
        $commentTypeDefault = $term->getter('id');
        $firstTermCount = 0;
      }
      if($term->getter('idName') === 'comment'){
        $commentTermID = $term->getter('id');
        $commentTypeTermsArray[$term->getter('id')] = __('A public comment and evaluation');
      }elseif ($term->getter('idName')  === 'suggest'){
        $suggestTermID = $term->getter('id');
        $commentTypeTermsArray[$term->getter('id')] = __('A private suggestion about this content');
      }
    }
    // Data Options Suggest Type
    $suggestTypeTax = $taxModelControl->listItems( array('filters' => array('idName' => 'suggestType')) )->fetch();
    $suggestTypeTerms = $termModelControl->listItems( array('filters' => array('taxgroup' => $suggestTypeTax->getter('id'))) )->fetchAll();
    $suggestTypeTermsArray = array();
    foreach ($suggestTypeTerms as $term) {
      $suggestTypeTermsArray[$term->getter('id')] = $term->getter('name');
    }
    // If exist user session
    $useraccesscontrol = new UserAccessController();
    $userSess = $useraccesscontrol->getSessiondata();
    if($userSess){
      $userID = $userSess['data']['id'];
    }else{
      $userID = NULL;
    }

    $form = new FormController('commentForm'); //actionform
    $form->setAction('/comment/sendcommentform');
    $form->setSuccess( 'jsEval', 'geozzy.commentInstance.successCommentBox();' );

    $fieldsInfo = array();
    $fieldsInfo['id'] = array(
      'params' => array( 'type' => 'reserved', 'value' => null )
    );
    if( count($commentTypeTermsArray) > 1){
      $fieldsInfo['type'] = array(
        'params' => array( 'type' => 'radio', 'label' => __('What do you contribute?'), 'value' => $commentTypeDefault,
          'options'=> $commentTypeTermsArray
        ),
        'rules' => array( 'required' => true )
      );
    }else{
      $fieldsInfo['type'] = array(
        'params' => array( 'type' => 'reserved', 'value' => $commentTypeDefault )
      );
    }
    if(!$userID){
      $fieldsInfo['anonymousName'] = array(
        'params' => array( 'label' => __('Your name')),
        'rules' => array( 'required' => true )
      );
      $fieldsInfo['anonymousEmail'] = array(
        'params' => array( 'label' => __('Your email')),
        'rules' => array( 'required' => true )
      );
    }
    if(in_array('comment', $ctypeParamTerms)){
      $fieldsInfo['rate'] = array(
        'params' => array( 'label' => __('How do we value?'), 'class' => 'inputRating', 'value' => 0 )
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
    }else{
      $commentCustomScript = '<script> var suggestTermID = false;  var commentTermID = false; </script>';
    }

    $template->assign("commentCustomScript", $commentCustomScript);
    $template->setTpl('comment.tpl', 'rextComment');
    $template->exec();

  }


  public function sendCommentForm() {
    $form = $this->actionCommentForm();
    $this->commentOk($form);
    $form->sendJsonResponse();
  }
  public function actionCommentForm(){
    $form = new FormController();
    if( $form->loadPostInput() ) {
      $form->validateForm();
    }
    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();
      if(!$valuesArray['resource']){
        $form->addFormError(__('An unexpected error has occurred'));
      }else{
        //Comprobar que el recurso tiene comentarios o sugerencias activadas en Conf y en BBDD
      }
    }

    return $form;
  }
  public function commentOk($form){
    //Si tod0 esta OK!
    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();

      $valuesArray['timeCreation'] = date("Y-m-d H:i:s", time());
      //Añadir valor de published si dependiendo de conf de comentarios
      $comment = new CommentModel( $valuesArray );

      $comment->save();
    }
    return $comment;
  }

}
