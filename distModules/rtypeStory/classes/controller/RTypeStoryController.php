<?php

class RTypeStoryController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeStory() );
  }


  /**
   * Alteramos el objeto form. del recursoBase para adaptarlo a las necesidades del RType
   *
   * @param $form FormController Objeto form. del recursoBase
   *
   * @return array $rTypeFieldNames
   */
  public function manipulateForm( FormController $form ) {

    // Lanzamos los manipulateForm de las extensiones
    parent::manipulateForm( $form );


    // Cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam( 'externalUrl', 'label', __( 'Home URL' ) );
    $form->setFieldParam( 'topics', 'type', 'reserved' );
    $form->setFieldParam( 'starred', 'type', 'reserved' );
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');
  } // function manipulateForm()


  /**
   * Preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {

    if( $form->getFieldValue('rExtView_viewAlternativeMode') == '' ) {
      $taxtermResult = ( new TaxonomyViewModel() )->listItems([
        'filters' => ['taxGroupIdName' => 'viewAlternativeMode', 'idName'=>'viewAppStory' ], 'cache' => $this->cacheQuery
      ]);

      if( $taxterm = $taxtermResult->fetch() ) {
        //var_dump($taxterm->getter('id'). ' ' . $taxterm->getter('idName').' ' .$taxterm->getter('name') );
        $form->setFieldValue('rExtView_viewAlternativeMode', $taxterm->getter('id'));
      }
      else {
        cogumelo::error('DEBE EXISTIR taxonomia viewAppStory para $rExtViewAppInitialTerms');
      }


    }


    // if( isset( $formBlockInfo['ext']['rextView']['template']['basic'] ) ) {

    // Cargamos la informacion del form, los datos y lanzamos los getFormBlockInfo de las extensiones
    $formBlockInfo = parent::getFormBlockInfo( $form );

    $templates = $formBlockInfo['template'];

    // TEMPLATE panel principa del form. Contiene los elementos globales del form.
    $templates['formBase'] = new Template();
    $templates['formBase']->setTpl( 'rTypeFormBase.tpl', 'geozzy' );
    $templates['formBase']->assign( 'title', __('Main Resource information') );
    $templates['formBase']->assign( 'res', $formBlockInfo );

    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'title' ),
      $form->multilangFieldNames( 'shortDescription' ),
      $form->multilangFieldNames( 'mediumDescription' ),
      $form->multilangFieldNames( 'content' )
    );
    $formFieldsNames[] = 'externalUrl';
    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel stepCollection
    $templates['stepCollection'] = new Template();
    $templates['stepCollection']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['stepCollection']->assign( 'title', __( 'Steps collection' ) );
    $templates['stepCollection']->setFragment( 'blockContent', $formBlockInfo['ext']['rextStory']['template']['full'] );



    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );

    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['social'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['stepCollection'] );
    if(class_exists( 'rextComment' ) && in_array('rextComment', $this->rExts)) {
      $templates['adminFull']->addToFragment( 'col8', $templates['comment'] );
    }
    $templates['adminFull']->addToFragment( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToFragment( 'col4', $templates['publication'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['image'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['rextView'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['info'] );


    // TEMPLATE en bruto con todos los elementos del form
    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rTypeFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'res', $formBlockInfo );

    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }


  /**
   * Validaciones extra previas a usar los datos del recurso
   *
   * @param $form FormController Objeto form. del recurso
   */
  // parent::resFormRevalidate( $form );


  /**
   * Creación-Edicion-Borrado de los elementos del recurso segun el RType
   *
   * @param $form FormController Objeto form. del recurso
   * @param $resource ResourceModel Objeto form. del recurso
   */
  // parent::resFormProcess( $form, $resource );


  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource );


  /**
   * Preparamos los datos para visualizar el Recurso con sus cambios y sus extensiones
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'ext' => array }
   */
  public function getViewBlockInfo( $resId = false ) {

    // Preparamos los datos para visualizar el Recurso con sus extensiones
    $viewBlockInfo = parent::getViewBlockInfo( $resId );


    // $template = new Template();
    $template = $viewBlockInfo['template']['full'];

    // IMPORTANTE: rExtView seguramente cambie el .tpl, ignorando el definido aqui
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeStory' );


    $collectionArrayInfo = $this->defResCtrl->getCollectionBlockInfo( $viewBlockInfo['data'][ 'id' ] );


    // $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );
    $viewBlockInfo['template']['full'] = $template;


    //
    // IMPORTANTE: rExtView se usa para alterar el resultado final del view
    // Hay que ejecutar siempre como paso final el siguiente metodo
    //
    $rextViewCtrl = new RExtViewController( $this );
    return $rextViewCtrl->alterViewBlockInfo( $viewBlockInfo, 'full' );
  }

} // class RTypeStoryController
