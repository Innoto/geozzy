<?php
Cogumelo::load('coreView/View.php');
Cogumelo::autoIncludes();

/**
* Clase Master to extend other application methods
*/
class DocAPIView extends View
{

  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    return true;
  }

  function main(){
    $this->template->setTpl('doc.tpl', 'geozzyAPI');
    $this->template->assign('swaggerLocation', '/vendor/yarn/swagger-ui/');
    $this->template->exec();
  }


  function apidocJson() {

    global $GEOZZY_API_DOC_URLS;

    $apis = json_encode( $GEOZZY_API_DOC_URLS );

    header('Content-type: application/json');
    echo '
      {
        "apiVersion": "1.0",
        "swaggerVersion": "2.0",
        "basePath": "'.SITE_HOST.'/api",
        "apis": '.$apis.'
      }
    ';



  }
}
