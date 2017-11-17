<?php
search::autoIncludes();
// geozzy::load( 'controller/RTypeController.php' );


class SearchView {

  private $searchCtrl = null;

  public function __construct() {

    $this->searchCtrl = new SearchController();
  }

  public function createIndex() {
    header('Content-Type: text/plain');

    $searchInfo = $this->searchCtrl->createIndexBase();

    echo $searchInfo;
  }

  public function getValueTr( $obj, $fieldName, $lang ) {
    $value = $obj->getter($fieldName.'_'.$lang);
    if( $lang !== $this->defLang && empty( $value ) ) {
      $value = $obj->getter($fieldName.'_'.$this->defLang);
    }

    return $value;
  }


  public function buscamos() {
    $response = 'NADA';

    header('Content-Type: text/plain');

    if( !empty($_GET['s']) ) {
      $response = $this->searchCtrl->search( $_GET['s'] );
      echo "\n\n --- RESULTADOS: ".count($response['hits'])."/".$response['total']." --- \n\n";
    }


    $this->mostrar($response);
  }


  public function mostrar( $datos ) {
    $pr = print_r( $datos, true );

    $pat = ['/^\s*[\(\)]?\s*\n/m', '/    /'];
    $sus = ['', '  '];
    echo preg_replace( $pat, $sus, $pr );
  }





  //
  //
  //
  // public function buscamos_1() {
  //   header('Content-Type: text/plain');
  //
  //   $params = [
  //     'index' => 'resource',
  //     'type' => 'base',
  //     'body' => [
  //       'query' => [
  //
  //       ]
  //     ]
  //   ];
  //
  //   $matchs = [];
  //
  //   if( !empty($_GET['s']) ) {
  //     $matchs[] = [ 'match' => [ 'searchAllText' => $_GET['s'] ] ];
  //   }
  //   if( !empty($_GET['r']) ) {
  //     $matchs[] = [ 'match' => [ 'rTypeIdName' => $_GET['r'] ] ];
  //   }
  //
  //   $params['body']['query'] = $matchs;
  //
  //   if( count($matchs) === 1 ) {
  //     $params['body']['query'] = $matchs[0];
  //   }
  //   elseif( count($matchs) > 1 ) {
  //     if( !empty($_GET['or']) ) {
  //       $params['body']['query'] = [
  //         'bool' => [
  //           'should' => $matchs
  //         ]
  //       ];
  //     }
  //     else {
  //       $params['body']['query'] = [
  //         'bool' => [
  //           'must' => $matchs
  //         ]
  //       ];
  //     }
  //   }
  //   $this->mostrar($params);
  //
  //
  //   $response = $this->searchCtrl->search($params);
  //   echo "\n\n --- RESULTADOS: ".$response['hits']['total']." --- \n\n";
  //   $this->mostrar($response);
  // }
  //
  //
  // public function showInfo() {
  //   $searchInfo = "\n\nshowInfo FIN\n\n";
  //
  //   header('Content-Type: text/plain');
  //
  //   $params = [ 'index' => 'resource', 'type' => 'base', 'id' => '22_gl' ];
  //   $response = $this->searchCtrl->get($params);
  //   echo "\n\n --- GET 22 \n"; $this->mostrar($response);
  //
  //
  //   $params = [
  //     'index' => 'resource',
  //     'type' => 'base',
  //     'body' => [
  //       'query' => [
  //         'bool' => [
  //           // 'must' => [
  //           //   'match_all' => []
  //           // ],
  //           'filter' => [
  //             'geo_distance' => [
  //               'distance' => '150m',
  //               'location' => [
  //                 'lat' => 43.35,
  //                 'lon' => -8.35
  //               ]
  //             ]
  //           ]
  //         ]
  //       ]
  //     ]
  //   ];
  //   $response = $this->searchCtrl->search($params);
  //   echo "\n\n --- SEARCH GEO \n"; $this->mostrar($response);
  //
  //
  //   $params = [
  //     'index' => 'resource',
  //     'type' => 'base',
  //     'body' => [
  //       'query' => [
  //         'match' => [
  //           'searchAllText' => 'deixar'
  //         ]
  //       ]
  //     ]
  //   ];
  //   $response = $this->searchCtrl->search($params);
  //   echo "\n\n --- SEARCH vasto \n"; $this->mostrar($response);
  //
  //
  //   $params = [
  //     'index' => 'resource',
  //     'type' => 'base',
  //     'body' => [
  //       'query' => [
  //         'match' => [
  //           'rTypeIdName' => 'rtypeStoryStep'
  //         ]
  //       ]
  //     ]
  //   ];
  //   $response = $this->searchCtrl->search($params);
  //   echo "\n\n --- SEARCH rtypeStoryStep \n"; $this->mostrar($response);
  //
  //
  //   $params = [ 'index' => 'resource' ];
  //   $response = $this->searchCtrl->indices()->getSettings($params);
  //   echo "\n\n --- getSettings \n"; $this->mostrar($response);
  //
  //
  //   echo $searchInfo;
  // }
  //
  //
  // public function createIndexDemo() {
  //   $searchInfo = "\n\nFIN\n\n";
  //
  //   header('Content-Type: text/plain');
  //
  //   $params = ['index' => 'blog'];
  //   $response = $this->searchCtrl->indices()->delete($params);
  //
  //   $params = [
  //     'index' => 'blog',
  //     'type' => 'user',
  //     'id' => 'dilbert',
  //     'body' => [
  //       'name' => 'Dilbert Brown',
  //     ]
  //   ];
  //   $response = $this->searchCtrl->index($params);
  //   $this->mostrar($response);
  //
  //   $params = [
  //     'index' => 'blog',
  //     'type' => 'post',
  //     'id' => '1',
  //     'body' => [
  //       'user' => 'dilbert',
  //       'postDate' => '2011-12-15',
  //       'body' => 'Search is hard. Search should be easy.' ,
  //       'title' => 'On search'
  //     ]
  //   ];
  //   $response = $this->searchCtrl->index($params);
  //   $this->mostrar($response);
  //
  //   $params = [
  //     'index' => 'blog',
  //     'type' => 'post',
  //     'id' => '2',
  //     'body' => [
  //       'user' => 'dilbert',
  //       'postDate' => '2011-12-12',
  //       'body' => 'Distribution is hard. Distribution should be easy.' ,
  //       'title' => 'On distributed search'
  //     ]
  //   ];
  //   $response = $this->searchCtrl->index($params);
  //   $this->mostrar($response);
  //
  //   $params = [
  //     'index' => 'blog',
  //     'type' => 'post',
  //     'id' => '3',
  //     'body' => [
  //       'user' => 'dilbert',
  //       'postDate' => '2011-12-10',
  //       'body' => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.\n  Os campos son de tipo texto e con un tamaño máximo de indexación de 256 caracteres.\n  O que pasa de AQUI xa non entra - raposo axouxere",
  //       'title' => 'Lorem ipsum'
  //     ]
  //   ];
  //   $response = $this->searchCtrl->index($params);
  //   $this->mostrar($response);
  //
  //   return $searchInfo;
  // }
  //
  // public function showInfoDemo() {
  //   $searchInfo = "\n\nFIN\n\n";
  //
  //   header('Content-Type: text/plain');
  //
  //   // $params = [
  //   //   'index' => 'blog',
  //   //   'type' => 'user',
  //   //   'id' => 'dilbert',
  //   // ];
  //   // $response = $this->searchCtrl->get($params);
  //   // echo "\n\n GET dilbert \n"; $this->mostrar($response);
  //
  //   // $params = [
  //   //   'index' => 'blog',
  //   //   'type' => 'post',
  //   //   'id' => '3',
  //   // ];
  //   // $response = $this->searchCtrl->get($params);
  //   // echo "\n\n GET 3 \n"; $this->mostrar($response);
  //
  //   // $params = [ 'index' => 'blog' ];
  //   // $response = $this->searchCtrl->indices()->getSettings($params);
  //   // echo "\n\n getSettings \n"; $this->mostrar($response);
  //
  //   $params = [
  //     'index' => 'blog',
  //     'type' => 'post',
  //     'body' => [
  //       'query' => [
  //         'match' => [
  //           'title' => 'search'
  //         ]
  //       ]
  //     ]
  //   ];
  //   $response = $this->searchCtrl->search($params);
  //   echo "\n\n SEARCH search \n"; $this->mostrar($response);
  //
  //   $params = [
  //     'index' => 'blog',
  //     'type' => 'post',
  //     'body' => [
  //       'query' => [
  //         'match' => [
  //           'body' => 'máximo tamaño'
  //         ]
  //       ]
  //     ]
  //   ];
  //   $response = $this->searchCtrl->search($params);
  //   echo "\n\n SEARCH máximo tamaño \n"; $this->mostrar($response);
  //
  //   echo $searchInfo;
  // }
  //
  //
  //
} // END SearchView class
