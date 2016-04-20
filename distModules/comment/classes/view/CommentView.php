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

    // Data Options Comment Type
    $taxModelControl = new TaxonomygroupModel();
    $termModelControl = new TaxonomytermModel();
    $commentTypeTax = $taxModelControl->listItems( array('filters' => array('idName' => 'commentType')) )->fetch();
    $commentTypeTerms = $termModelControl->listItems( array('filters' => array('taxgroup' => $commentTypeTax->getter('id'))) )->fetchAll();
    $commentTypeTermsArray = array();
    foreach ($commentTypeTerms as $term) {
      if($term->getter('idName') === 'comment'){
        $commentTypeTermsArray[$term->getter('id')] = __('A public comment and evaluation');
      }elseif ($term->getter('idName')  === 'suggest'){
        $commentTypeTermsArray[$term->getter('id')] = __('A private suggestion about this content');
      }
    }
    $suggestTypeTax = $taxModelControl->listItems( array('filters' => array('idName' => 'suggestType')) )->fetch();
    $suggestTypeTerms = $termModelControl->listItems( array('filters' => array('taxgroup' => $suggestTypeTax->getter('id'))) )->fetchAll();
    $suggestTypeTermsArray = array();
    foreach ($suggestTypeTerms as $term) {
      $suggestTypeTermsArray[$term->getter('id')] = $term->getter('name');
    }

    $fieldsInfo = array(
      'id' => array(
        'params' => array( 'type' => 'reserved', 'value' => null )
      ),
      'type' => array(
        'params' => array( 'type' => 'radio', 'label' => __('What do you contribute?'),
          'options'=> $commentTypeTermsArray
        ),
        'rules' => array( 'required' => true )
      ),
      'rate' => array(
        'params' => array( 'label' => __('How do we value?'), 'value' => 0 ),
      ),
      'suggestType' => array(
        'params' => array( 'label' => __( 'What do we suggest?' ), 'type' => 'select',
          'options'=> $suggestTypeTermsArray
        )
      ),
      'content' => array(
        'params' => array( 'label' => __( 'Your comment' ), 'type' => 'textarea' ),
        'rules' => array( 'required' => true )
      ),
      'user' => array(
        'params' => array( 'type' => 'reserved')
      ),
      'anonymousName' => array(
        'params' => array( 'type' => 'reserved')
      ),
      'anonymousEmail' => array(
        'params' => array( 'type' => 'reserved')
      ),
      'resource' => array(
        'params' => array( 'type' => 'reserved')
      ),
      'published' => array(
        'params' => array( 'type' => 'reserved')
      ),
      'cancel' => array(
        'params' => array( 'type' => 'button', 'value' => __('Cancel') )
      ),
      'submit' => array(
        'params' => array( 'type' => 'submit', 'value' => __('Send') )
      )
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
      //Comprobar que el recurso tiene comentarios o sugerencias activadas

      //Validaciones extra
      $userControl = new UserModel();
      // Donde diferenciamos si es un update o un create para validar el login
      $loginExist = $userControl->listItems( array('filters' => array('email' => $form->getFieldValue('email'))) )->fetch();

      if( isset($valuesArray['id']) && $valuesArray['id'] ){
        $user = $userControl->listItems( array('filters' => array('id' => $valuesArray['id'])) )->fetch();
        if($valuesArray['email'] !== $user->getter('email')){
          if($loginExist){
            $form->addFieldRuleError('email', 'cogumelo', 'El campo email específicado ya esta en uso.');
          }
        }
      }
      else{
        // Create: comprobamos si el login existe y si existe mostramos error.
        if($loginExist){
          $form->addFieldRuleError('email', 'cogumelo', 'El campo email específicado ya esta en uso.');
        }
      }
    }

    return $form;
  }
  public function commentOk($form){
    //Si tod0 esta OK!
    $asignRole = false;

    if( !$form->processFileFields() ) {
      $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos. Puede que sea necesario subirlos otra vez.', 'formError' );
    }

    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();

      $password = false;

      if( array_key_exists('password', $valuesArray)){
        $password = $valuesArray['password'];
        unset($valuesArray['password']);
        unset($valuesArray['password2']);
      }
       // Donde diferenciamos si es un update o un create
      if( !isset($valuesArray['id']) || !$valuesArray['id'] ){
        $valuesArray['timeCreateUser'] = date("Y-m-d H:i:s", time());
        $asignRole = true;
      }
      $valuesArray['login'] = $valuesArray['email'];
      if( array_key_exists( 'avatar', $valuesArray) ){
        $userAvatar = $valuesArray['avatar'];
        unset($valuesArray['avatar']);
      }
      $user = new UserModel( $valuesArray );

      if(isset($password) && $password){
        $user->setPassword( $password );
      }

      $user->save();

      //var_dump( $user->getAllData() );

      if( isset($userAvatar) && $userAvatar ) {
        //var_dump( $userAvatar );
        /*
          if( $userAvatar['status'] === "DELETE"){
            //IMG DELETE
            //var_dump('delete');
            $user->deleteDependence( 'avatar', true );
          }
          elseif( $userAvatar['status'] === "REPLACE"){
            //IMG UPDATE
            //var_dump('replace');
            $user->deleteDependence( 'avatar', true);
            $user->setterDependence( 'avatar', new FiledataModel( $userAvatar['values'] ) );
          }else{
            //var_dump('else');
            //IMG CREATE
            $user->setterDependence( 'avatar', new FiledataModel( $userAvatar['values'] ) );
          }
        */


        $filedataCtrl = new FiledataController();
        $newFiledataObj = false;

        switch( $userAvatar['status'] ) {
          case 'LOADED':
            $userAvatarValues = $userAvatar['values'];
            $newFiledataObj = $filedataCtrl->createNewFile( $userAvatarValues );
            // error_log( 'To Model - newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
            if( $newFiledataObj ) {
              // $user->setterDependence( 'avatar', $newFiledataObj );
              $user->setter( 'avatar', $newFiledataObj->getter( 'id' ) );
            }
            break;
          case 'REPLACE':
            // error_log( 'To Model - fileInfoPrev: '. print_r( $userAvatar[ 'prev' ], true ) );
            $userAvatarValues = $userAvatar['values'];
            $prevFiledataId = $user->getter( 'avatar' );
            $newFiledataObj = $filedataCtrl->createNewFile( $userAvatarValues );
            // error_log( 'To Model - newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
            if( $newFiledataObj ) {
              // error_log( 'To Model - deleteFile ID: '.$prevFiledataId );
              // $user->deleteDependence( 'avatar', true );
              // $user->setterDependence( 'avatar', $newFiledataObj );
              $user->setter( 'avatar', $newFiledataObj->getter( 'id' ) );
              $filedataCtrl->deleteFile( $prevFiledataId );
            }
            break;
          case 'DELETE':
            if( $prevFiledataId = $user->getter( 'avatar' ) ) {
              // error_log( 'To Model - prevFiledataId: '.$prevFiledataId );
              $filedataCtrl->deleteFile( $prevFiledataId );
              // $user->deleteDependence( 'avatar', true );
              $user->setter( 'avatar', null );
            }
            break;
          case 'EXIST':
            $userAvatarValues = $userAvatar[ 'values' ];
            if( $prevFiledataId = $user->getter( 'avatar' ) ) {
              // error_log( 'To Model - UPDATE prevFiledataId: '.$prevFiledataId );
              $filedataCtrl->updateInfo( $prevFiledataId, $userAvatarValues );
            }
            break;
          default:
            // error_log( 'To Model: DEFAULT='.$userAvatar['status'] );
            break;
        } // switch( $userAvatar['status'] )



      } // if( $userAvatar )

      //echo "==========";
      //var_dump( $user->getAllData()  );

      $user->save();
      // $user->save( array( 'affectsDependences' => true ) );

      /*Asignacion de ROLE user*/
      if($asignRole){
        $roleModel = new RoleModel();
        $role = $roleModel->listItems( array('filters' => array('name' => 'user') ))->fetch();
        $userRole = new UserRoleModel();
        if( $role ){
          $userRole->setterDependence( 'role', $role );
        }
        $userRole->setterDependence( 'user', $user );
        $userRole->save(array( 'affectsDependences' => true ));
      }

    }
    return $user;
  }

}
