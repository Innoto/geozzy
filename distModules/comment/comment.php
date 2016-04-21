<?php


Cogumelo::load("coreController/Module.php");


class comment extends Module
{
  public $name = "comment";
  public $version = 1.0;


  public $dependences = array(
    array(
     "id" =>"underscore",
     "params" => array("underscore#1.8.3"),
     "installer" => "bower",
     "includes" => array("underscore-min.js")
    ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone#1.1.2"),
     "installer" => "bower",
     "includes" => array("backbone.js")
    )
  );

  public $includesCommon = array(
/*
    'js/router/UserRouter.js',
    'js/model/UserSessionModel.js',
    'js/view/Templates.js',
    'js/view/UserLoginBoxView.js',
    'js/view/UserRegisterBoxView.js',
    'js/view/UserRegisterOkBoxView.js',
    'js/UserSession.js',
    'js/UserSessionInstance.js'
*/
  );

  public $taxonomies = array(
    'commentType' => array(
      'idName' => 'commentType',
      'name' => array(
        'en' => 'Comment type',
        'es' => 'Tipo de comentario',
        'gl' => 'Tipo de comentario'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        array(
          'idName' => 'comment',
          'name' => array(
            'en' => 'Comment',
            'es' => 'Comentario',
            'gl' => 'Comentario'
          )
        ),
        array(
          'idName' => 'suggest',
          'name' => array(
            'en' => 'Suggest',
            'es' => 'Sugerencia',
            'gl' => 'Suxerencia'
          )
        )
      )
    ),
    // Suggest Status
    'commentStatus' => array(
      'idName' => 'commentStatus',
      'name' => array(
        'en' => 'Comment status',
        'es' => 'Estados de Comentario',
        'gl' => 'Estados de Comentario'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        array(
          'idName' => 'commentValidated',
          'name' => array(
            'en' => 'Validated',
            'es' => 'Validado',
            'gl' => 'Validado'
          )
        ),
        array(
          'idName' => 'commentNotValidated',
          'name' => array(
            'en' => 'Not Validated',
            'es' => 'No Validado',
            'gl' => 'No Validado'
          )
        ),
        array(
          'idName' => 'commentDenied',
          'name' => array(
            'en' => 'Denied',
            'es' => 'Denegado',
            'gl' => 'Denegado'
          )
        )
      )
    ),
    //suggestType
    'suggestType' => array(
      'idName' => 'suggestType',
      'name' => array(
        'en' => 'Suggest Type',
        'es' => 'Tipo de sugerencia',
        'gl' => 'Tipo de suxerencia'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        array(
          'idName' => 'error',
          'name' => array(
            'en' => 'Error',
            'es' => 'Error',
            'gl' => 'Error'
          )
        ),
        array(
          'idName' => 'improvement',
          'name' => array(
            'en' => 'improvement',
            'es' => 'Mejora',
            'gl' => 'Mellora'
          )
        )
      )
    )
  );

  function __construct() {
    $this->addUrlPatterns( '#^comment/form#', 'view:CommentView::commentForm' );
    $this->addUrlPatterns( '#^comment/sendcommentform$#', 'view:CommentView::sendCommentForm' );
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
