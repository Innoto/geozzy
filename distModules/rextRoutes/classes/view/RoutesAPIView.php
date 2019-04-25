<?php

Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class RoutesAPIView extends View
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return( true );
  }


  public function routesJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/routes.json",
      "basePath": "/api",
      "apis": [
        {
          "operations": [
            {
              "errorResponses": [
                {
                  "reason": "The explorer",
                  "code": 200
                },
                {
                  "reason": "Explorer not found",
                  "code": 404
                }
              ],
              "httpMethod": "POST",
              "nickname": "explorer",
              "parameters": [
                {
                  "name": "id",
                  "description": "id",
                  "type": "array",
                  "items": {
                    "type": "integer"
                  },
                  "paramType": "path",
                  "required": false
                },
                {
                  "name": "resolution",
                  "description": "resolution (according gmaps zoom level values)",
                  "type": "array",
                  "items": {
                    "type": "integer"
                  },
                  "paramType": "path",
                  "required": false
                }
              ],
              "summary": "Fetches explorer data"
            }
          ],
          "path": "/routes/id/{id}/resolution/{resolution}",
          "description": ""
        }
      ]
    }
    <?php
  }



  public function routes( $urlParams  ) {




    $cacheRouteQueryKey = 'rextRoutesCache';
    $retRoute = '[]';

    $validation = array( 'id'=> '#^\d+$#', 'resolution'=> '#^\d+$#' );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );


    rextRoutes::autoIncludes();
    rextRoutes::load('controller/RoutesController.php');
    Cogumelo::load('coreController/Cache.php');

    $routeCacheControl =  new Cache();
    $routesControl = new RoutesController();


    header('Content-type: application/json');




    $retRoute = '[]';

    if( isset($urlParamsList['id']) ) {

      $resourceCtrl = new ResourceModel();
      $resourceRsl = $resourceCtrl->listItems([ 'filters'=>[ 'id'=>$urlParamsList['id'] ] ]);
      $resBaseRoute = $resourceRsl->fetch();

      if( $resBaseRoute && $resBaseRoute->getter('published') == true ) {

        $cacheRouteQueryKey .= ( isset($urlParamsList['id']) )? '_'.$urlParamsList['id'] : '' ;
        $cacheRouteQueryKey .= ( isset($urlParamsList['resolution']) )? '_'.$urlParamsList['resolution'] : '' ;

        if( $cachedRoute = $routeCacheControl->getCache( $cacheRouteQueryKey ) ) {

          $retRoute = $cachedRoute;
        }
        else {

          if( isset($urlParamsList['resolution']) ) {
            $retRoute = json_encode(  $routesControl->getRoute( $urlParamsList['id'], $urlParamsList['resolution'] ) );
          }
          else {
            $retRoute = json_encode(  $routesControl->getRoute( $urlParamsList['id'] ) );
          }

          $routeCacheControl->setCache( $cacheRouteQueryKey, $retRoute, Cogumelo::getSetupValue( 'mod:mediaserver:publicConf:javascript:vars:rextRoutesConf:cacheTime' ));
        }

      }

    }

    echo $retRoute;

  }


  public function adminRoutes( $urlParams  ) {


    $validation = array( 'idForm'=> '#(.*)#');
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );


    rextRoutes::autoIncludes();
    rextRoutes::load('controller/RoutesController.php');
    $useraccesscontrol = new UserAccessController();
    $routesControl = new RoutesController();


    header('Content-type: application/json');



    if(
      isset($urlParamsList['idForm'])  &&
      $useraccesscontrol->checkPermissions( 'admin:access' )
    ) {
      echo json_encode(  $routesControl->getRouteInForm( $urlParamsList['idForm'] ) );
    }
    else {
      echo '[]';
    }


  }


}
