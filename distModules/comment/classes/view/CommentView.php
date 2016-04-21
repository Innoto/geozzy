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

  public function commentForm() {

    $form = new FormController('commentForm'); //actionform
    $form->setAction('/comment/sendcommentform');
    //$form->setSuccess( 'jsEval', 'geozzy.userSessionInstance.successRegisterBox();' );

    $taxModelControl = new TaxonomygroupModel();
    $termModelControl = new TaxonomytermModel();
    // Data Options Comment Type
    $commentTypeTax = $taxModelControl->listItems( array('filters' => array('idName' => 'commentType')) )->fetch();
    $commentTypeTerms = $termModelControl->listItems( array('filters' => array('taxgroup' => $commentTypeTax->getter('id'))) )->fetchAll();
    $commentTypeTermsArray = array();
    foreach ($commentTypeTerms as $term) {
      if($term->getter('idName') === 'comment'){
        $commentTypeTermsArray[$term->getter('id')] = __('A public comment and evaluation');
        $commentTypeDefault = $term->getter('id');
      }elseif ($term->getter('idName')  === 'suggest'){
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

    $fieldsInfo = array();
    $fieldsInfo['id'] = array(
      'params' => array( 'type' => 'reserved', 'value' => null )
    );
    $fieldsInfo['type'] = array(
      'params' => array( 'type' => 'radio', 'label' => __('What do you contribute?'), 'value' => $commentTypeDefault,
        'options'=> $commentTypeTermsArray
      ),
      'rules' => array( 'required' => true )
    );
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
    $fieldsInfo['rate'] = array(
      'params' => array( 'label' => __('How do we value?'), 'value' => 0 )
    );
    $fieldsInfo['suggestType'] = array(
      'params' => array( 'label' => __( 'What do we suggest?' ), 'type' => 'select',
        'options'=> $suggestTypeTermsArray
      )
    );
    $fieldsInfo['content'] = array(
      'params' => array( 'label' => __( 'Your comment' ), 'type' => 'textarea' ),
      'rules' => array( 'required' => true )
    );
    $fieldsInfo['user'] = array(
      'params' => array( 'type' => 'reserved', 'value' => $userID )
    );
    $fieldsInfo['user'] = array(
      'params' => array( 'type' => 'reserved', 'value' => $userID )
    );
    $fieldsInfo['resource'] = array(
      'params' => array( 'type' => 'reserved' )
    );
    $fieldsInfo['cancel'] = array(
      'params' => array( 'type' => 'button', 'value' => __('Cancel') )
    );
    $fieldsInfo['submit'] = array(
      'params' => array( 'type' => 'submit', 'value' => __('Send') )
    );

    $form->definitionsToForm( $fieldsInfo );
    $form->saveToSession();

    $template = new Template( $this->baseDir );
    $template->assign("commentFormOpen", $form->getHtmpOpen());
    $template->assign("commentFormFields", $form->getHtmlFieldsArray());
    $template->assign("commentFormClose", $form->getHtmlClose());
    $template->assign("commentFormValidations", $form->getScriptCode());
    $template->setTpl('comment.tpl', 'comment');

    echo ( $template->execToString() );

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
      $valuesArray['published'] = true;
      $comment = new CommentModel( $valuesArray );
      $comment->save();
    }
    return $comment;
  }

}
