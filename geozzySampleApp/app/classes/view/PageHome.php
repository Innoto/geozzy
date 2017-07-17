<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
Cogumelo::autoIncludes();
geozzy::autoIncludes();
Cogumelo::load('coreController/MailController.php');

class PageHome {

  public function __construct() {
  }


  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    // error_log( 'alterViewBlockInfo en PageHome' );
    $resourceCtrl = new ResourceController();

    $templateName = ($templateName) ? $templateName : 'full';
    $template = $viewBlockInfo['template'][ $templateName ];

    $template->assign( 'isFront', true );
    $template->addClientScript( 'js/pageHome.js' );
    $template->addClientStyles( 'styles/masterPortada.less' );
    $template->setTpl( 'pageHome.tpl' );

    $form = new FormController( 'contactForm', '/contact/sendToMail' );

    $fieldsInfo = array(
      'name' => array(
        'params' => array( 'placeholder' => __( 'YOUR NAME' ) ),
        'rules' => array( 'required' => true, 'maxlength' => '250' )
      ),
      'email' => array(
        'params' => array( 'placeholder' => __( 'YOUR EMAIL' ) ),
        'rules' => array( 'required' => true, 'maxlength' => '250', 'email' => true )
      ),
      'phone' => array(
        'params' => array( 'placeholder' => __( 'YOUR PHONE' ) ),
        'rules' => array( 'required' => true, 'maxlength' => '20' )
      ),
      'info' => array(
        'params' => array( 'placeholder' => __( 'YOUR MESSAGE' ), 'type' => 'textarea' ),
        'rules' => array( 'required' => true, 'maxlength' => '4000' )
      ),
      'acceptCond' => array(
        'params' => array( 'type' => 'checkbox',
        'options' => array( 'accept' => __( 'Acepto la <a href="/politica-privacidad" target="_blank">política de privacidad</a>' ) ) ),
        'rules' => array( 'required' => true )
      ),
      'submit' => array(
        'params' => array( 'type' => 'submit', 'value' => __( 'Send message' ) )
      )
    );

    $form->definitionsToForm( $fieldsInfo );

    $form->setSuccess( 'jsEval', 'new geozzy.generateModal({ classCss: "contactEmailOK", htmlBody:"Tu consulta ha sido enviada correctamente", function(){}});');
    $form->setSuccess( 'resetForm' );

    $form->setSuccess( 'onFileUpload', 'fichSubido' );
    $form->setSuccess( 'onFileDelete', 'fichRetirado' );
    $form->setSuccess( 'onSubmitOk', 'correoEnviado' );
    $form->setSuccess( 'onSubmitError', 'correoFallido' );

    $form->setKeepAlive( true );

    $form->saveToSession();

    $template->assign( 'contactForm', $form->getHtmlForm() );


    // Mirar convocatoria activa
    $convIdName = false;
    $resTaxViewModel = new ResourceTaxonomyAllModel();
    $resTaxViewList = $resTaxViewModel->listItems( array(
      'filters' => array( 'idNameTaxgroup' => 'appConvocatoriaEstado' )
    ) );

    if( gettype( $resTaxViewList ) === 'object' ) {
      $resTaxViewObj = $resTaxViewList ? $resTaxViewList->fetch() : false;
      $convIdName = $resTaxViewObj ? $resTaxViewObj->getter('idName') : false;
    }
    $template->assign( 'convocatoria', $convIdName );

    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }

}
