<?php
Cogumelo::load("coreController/Module.php");

class rextComment extends Module {

  public $name = "rextComment";
  public $version = 1.3;

  public $models = array( 'CommentModel' );

  public $dependences = array(
    array(
      "id" =>"underscore",
      "params" => array("underscore@1.8.3"),
      "installer" => "yarn",
      "includes" => array("underscore-min.js")
    ),
    array(
      "id" =>"backbonejs",
      "params" => array("backbone@1.1.2"),
      "installer" => "yarn",
      "includes" => ['backbone.js']
    ),
    array(
      "id" => "font-awesome",
      "params" => array("font-awesome"),
      "installer" => "yarn",
      "includes" => array("css/font-awesome.min.css")
    ),
    array(
      "id" =>"bootstrap-star-rating",
      "params" => array("bootstrap-star-rating"),
      "installer" => "yarn",
      "includes" => array("js/star-rating.min.js", 'css/star-rating.min.css')
    ),
    array(
      'id' =>'moment',
      'params' => array( 'moment' ),
      'installer' => 'yarn',
      'includes' => array( 'min/moment-with-locales.min.js' )
    ),
    array(
      "id" =>"moment-timezone",
      "params" => array("moment-timezone"),
      "installer" => "yarn",
      "includes" => array("builds/moment-timezone-with-data.min.js")
    )
  );
  public $autoIncludeAlways = true;
  public $includesCommon = array(
    'controller/RExtCommentController.php',
    'js/model/CommentModel.js',
    'js/collection/CommentCollection.js',
    'js/collection/CommentSuggestionCollection.js',
    'js/view/Templates.js',
    'js/view/ListCommentView.js',
    'js/view/CreateCommentView.js',
    'js/view/CommentOkView.js',
    'js/view/AdminListCommentView.js',
    'js/Comment.js',
    'js/commentInstance.js'
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

  public function __construct() {
    // Form de creacion de comentario
    $this->addUrlPatterns( '#^comment/form/(.*)#', 'view:CommentView::commentForm' );
    // Action del Form de creacion de comentario
    $this->addUrlPatterns( '#^comment/sendcommentform$#', 'view:CommentView::sendCommentForm' );

    // API publica de comentarios
    $this->addUrlPatterns( '#^api/(comments(/.*)?)$#', 'view:CommentAPIView::comments' );
    $this->addUrlPatterns( '#^api/doc/comments.json$#', 'view:CommentAPIView::commentsDescription' );

    // API de listado de comentarios
    $this->addUrlPatterns( '#^api/comment/list/(.*)$#', 'view:CommentAPIView::commentList' );
    $this->addUrlPatterns( '#^api/doc/commentList.json$#', 'view:CommentAPIView::commentListDescription' );
  }

  function setGeozzyUrlPatternsAPI() {

    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();
    // APIs que requieren un usuario logueado con permisos admin:access o admin:full
    if( $useraccesscontrol->isLogged() && ($useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full')) )  {
      // API de administracion de comentarios
      $this->addUrlPatterns( '#^api/admin/commentsuggestion/(.*)$#', 'view:CommentAdminAPIView::commentsSuggestions' );
      $this->addUrlPatterns( '#^api/doc/admin/adminCommentSuggestion.json$#', 'view:CommentAdminAPIView::commentsSuggestionsJson' );
    }

  }

  function getGeozzyDocAPI() {
    $ret = array(
      array(
        'path'=> '/doc/comments.json',
        'description' => 'Comments API'
      ),
      array(
        'path'=> '/doc/commentList.json',
        'description' => 'Comment List API'
      )
    );

    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();
    // APIs que requieren un usuario logueado con permisos admin:access o admin:full
    if( $useraccesscontrol->isLogged() && ($useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full')) )  {
      $ret[] = array(
        'path' => '/doc/admin/adminCommentSuggestion.json',
        'description' => 'Admin comment suggestion'
      );
    }


    return $ret;
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }

  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleDeploy();
  }
}
