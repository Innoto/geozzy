<?php
search::autoIncludes();
// geozzy::load( 'controller/RTypeController.php' );


class SearchView {

  private $searchCtrl = null;

  public function __construct() {
    $hosts = [
      [
        'host' => 'localhost',
        'port' => '9200',
        'scheme' => 'http',
        'user' => 'elastic',
        'pass' => 'changeme'
      ]
    ];

    $this->searchCtrl = Elasticsearch\ClientBuilder::create()->setHosts( $hosts )->build();
  }



  public function createIndex() {
    $searchInfo = "\n\ncreateIndex FIN\n\n";

    header('Content-Type: text/plain');

    $params = ['index' => 'resource'];
    if( $this->searchCtrl->indices()->exists($params) ) {
      $response = $this->searchCtrl->indices()->delete($params);
    }


    $typeTextIndex = [ 'type' => 'text', 'copy_to' => 'searchAllText' ];
    $params = [
      'index' => 'resource',
      'body' => [
        'mappings' => [
          'base' => [
            'properties' => [
              'id' => [ 'type' => 'integer' ],
              'rTypeId' => [ 'type' => 'integer' ],
              'rTypeIdName' => [ 'type' => 'keyword' ], //only searchable by their exact value.
              'user' => [ 'type' => 'integer' ],
              'timeCreation' => [ 'type' => 'date' ],
              'timeLastUpdate' => [ 'type' => 'date' ],
              'title' => $typeTextIndex,
              'shortDescription' => $typeTextIndex,
              'mediumDescription' => $typeTextIndex,
              'content' => $typeTextIndex,
              'termsNames' => $typeTextIndex,
              'headKeywords' => $typeTextIndex,
              'headDescription' => $typeTextIndex,
              'headTitle' => $typeTextIndex,
              'urlAlias' => [ 'type' => 'text' ],
              'location' => [ 'type' => 'geo_point' ],
              'searchAllText' => [ 'type' => 'text' ], // Combined text fields
            ]
          ]
        ]
      ]
    ];
    // Create the index with mappings and settings now
    $response = $this->searchCtrl->indices()->create($params);


    $taxText = [];
    $taxModel = new ResourceTaxonomyAllModel();
    $taxList = $taxModel->listItems();
    if( is_object( $taxList ) ) {
      while( $taxObj = $taxList->fetch() ) {
        $resId = $taxObj->getter('resource');
        $name = $taxObj->getter('name');
        $taxText[ $resId ] = empty($taxText[ $resId ]) ? $name : $taxText[ $resId ].', '.$name;
      }
    }


    $resModel = new ResourceViewModel();
    $resList = $resModel->listItems([ 'filters' => [ 'published' => 1 ] ]);
    if( is_object( $resList ) ) {
      Cogumelo::load('coreModel/DBUtils.php');
      while( $resObj = $resList->fetch() ) {
        $resId = $resObj->getter('id');
        echo "\n Resource: ".$resId;
        // $timeCreation = strtr( $resObj->getter('timeCreation'), ' ', 'T').'Z';
        // $timeCreation = strtr($timeCreation,' ','T').'Z';
        // echo "\n timeCreation: ".$timeCreation."\n";
        $params = [
          'index' => 'resource',
          'type' => 'base',
          'id' => $resId,
          'body' => [
            'id' => $resId,
            'rTypeId' => $resObj->getter('rTypeId'),
            'rTypeIdName' => $resObj->getter('rTypeIdName'),
            'user' => $resObj->getter('user'),
            'title' => $resObj->getter('title'),
            'shortDescription' => $resObj->getter('shortDescription'),
            'mediumDescription' => html_entity_decode( strip_tags( $resObj->getter('mediumDescription') ) ),
            'content' => html_entity_decode( strip_tags( $resObj->getter('content') ) ),
            'headKeywords' => $resObj->getter('headKeywords'),
            'headDescription' => $resObj->getter('headDescription'),
            'headTitle' => $resObj->getter('headTitle'),
            'urlAlias' => $resObj->getter('urlAlias'),
          ]
        ];
        if( $timeCreation = $resObj->getter('timeCreation') ) {
          $params['body']['timeCreation'] = strtr( $timeCreation, ' ', 'T').'Z';
        }
        if( $timeLastUpdate = $resObj->getter('timeLastUpdate') ) {
          $params['body']['timeLastUpdate'] = strtr( $timeLastUpdate, ' ', 'T').'Z';
        }
        if( $loc = $resObj->getter('loc') ) {
          $geoLocation = DBUtils::decodeGeometry( $loc );
          // $params['body']['location'] = ''.$geoLocation['data'][0].','.$geoLocation['data'][1];
          $params['body']['location'] = [
            'lon' => $geoLocation['data'][1],
            'lat' => $geoLocation['data'][0],
          ];
        }
        if( !empty( $taxText[ $resId ] ) ) {
          $params['body']['termsNames'] = $taxText[ $resId ];
        }

        $response = $this->searchCtrl->index($params);
      }
    }


    echo $searchInfo;
  }

  public function buscamos() {
    header('Content-Type: text/plain');

    $params = [
      'index' => 'resource',
      'type' => 'base',
      'body' => [
        'query' => [

        ]
      ]
    ];

    $matchs = [];

    if( !empty($_GET['s']) ) {
      $matchs[] = [ 'match' => [ 'searchAllText' => $_GET['s'] ] ];
    }
    if( !empty($_GET['r']) ) {
      $matchs[] = [ 'match' => [ 'rTypeIdName' => $_GET['r'] ] ];
    }

    $params['body']['query'] = $matchs;

    if( count($matchs) === 1 ) {
      $params['body']['query'] = $matchs[0];
    }
    elseif( count($matchs) > 1 ) {
      if( !empty($_GET['or']) ) {
        $params['body']['query'] = [
          'bool' => [
            'should' => $matchs
          ]
        ];
      }
      else {
        $params['body']['query'] = [
          'bool' => [
            'must' => $matchs
          ]
        ];
      }
    }
    $this->mostrar($params);


    $response = $this->searchCtrl->search($params);
    echo "\n\n --- RESULTADOS: ".$response['hits']['total']." --- \n\n";
    $this->mostrar($response);
  }

  public function showInfo() {
    $searchInfo = "\n\nshowInfo FIN\n\n";

    header('Content-Type: text/plain');

    $params = [ 'index' => 'resource', 'type' => 'base', 'id' => '22' ];
    $response = $this->searchCtrl->get($params);
    echo "\n\n --- GET 22 \n"; $this->mostrar($response);


    $params = [
      'index' => 'resource',
      'type' => 'base',
      'body' => [
        'query' => [
          'bool' => [
            // 'must' => [
            //   'match_all' => []
            // ],
            'filter' => [
              'geo_distance' => [
                'distance' => '150m',
                'location' => [
                  'lat' => 43.35,
                  'lon' => -8.35
                ]
              ]
            ]
          ]
        ]
      ]
    ];
    $response = $this->searchCtrl->search($params);
    echo "\n\n --- SEARCH GEO \n"; $this->mostrar($response);


    $params = [
      'index' => 'resource',
      'type' => 'base',
      'body' => [
        'query' => [
          'match' => [
            'searchAllText' => 'deixar'
          ]
        ]
      ]
    ];
    $response = $this->searchCtrl->search($params);
    echo "\n\n --- SEARCH vasto \n"; $this->mostrar($response);


    $params = [
      'index' => 'resource',
      'type' => 'base',
      'body' => [
        'query' => [
          'match' => [
            'rTypeIdName' => 'rtypeStoryStep'
          ]
        ]
      ]
    ];
    $response = $this->searchCtrl->search($params);
    echo "\n\n --- SEARCH rtypeStoryStep \n"; $this->mostrar($response);


    $params = [ 'index' => 'resource' ];
    $response = $this->searchCtrl->indices()->getSettings($params);
    echo "\n\n --- getSettings \n"; $this->mostrar($response);


    echo $searchInfo;
  }



  public function createIndexDemo() {
    $searchInfo = "\n\nFIN\n\n";

    header('Content-Type: text/plain');

    $params = ['index' => 'blog'];
    $response = $this->searchCtrl->indices()->delete($params);

    $params = [
      'index' => 'blog',
      'type' => 'user',
      'id' => 'dilbert',
      'body' => [
        'name' => 'Dilbert Brown',
      ]
    ];
    $response = $this->searchCtrl->index($params);
    $this->mostrar($response);

    $params = [
      'index' => 'blog',
      'type' => 'post',
      'id' => '1',
      'body' => [
        'user' => 'dilbert',
        'postDate' => '2011-12-15',
        'body' => 'Search is hard. Search should be easy.' ,
        'title' => 'On search'
      ]
    ];
    $response = $this->searchCtrl->index($params);
    $this->mostrar($response);

    $params = [
      'index' => 'blog',
      'type' => 'post',
      'id' => '2',
      'body' => [
        'user' => 'dilbert',
        'postDate' => '2011-12-12',
        'body' => 'Distribution is hard. Distribution should be easy.' ,
        'title' => 'On distributed search'
      ]
    ];
    $response = $this->searchCtrl->index($params);
    $this->mostrar($response);

    $params = [
      'index' => 'blog',
      'type' => 'post',
      'id' => '3',
      'body' => [
        'user' => 'dilbert',
        'postDate' => '2011-12-10',
        'body' => "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.\n  Os campos son de tipo texto e con un tamaño máximo de indexación de 256 caracteres.\n  O que pasa de AQUI xa non entra - raposo axouxere",
        'title' => 'Lorem ipsum'
      ]
    ];
    $response = $this->searchCtrl->index($params);
    $this->mostrar($response);

    return $searchInfo;
  }

  public function showInfoDemo() {
    $searchInfo = "\n\nFIN\n\n";

    header('Content-Type: text/plain');

    // $params = [
    //   'index' => 'blog',
    //   'type' => 'user',
    //   'id' => 'dilbert',
    // ];
    // $response = $this->searchCtrl->get($params);
    // echo "\n\n GET dilbert \n"; $this->mostrar($response);

    // $params = [
    //   'index' => 'blog',
    //   'type' => 'post',
    //   'id' => '3',
    // ];
    // $response = $this->searchCtrl->get($params);
    // echo "\n\n GET 3 \n"; $this->mostrar($response);

    // $params = [ 'index' => 'blog' ];
    // $response = $this->searchCtrl->indices()->getSettings($params);
    // echo "\n\n getSettings \n"; $this->mostrar($response);

    $params = [
      'index' => 'blog',
      'type' => 'post',
      'body' => [
        'query' => [
          'match' => [
            'title' => 'search'
          ]
        ]
      ]
    ];
    $response = $this->searchCtrl->search($params);
    echo "\n\n SEARCH search \n"; $this->mostrar($response);

    $params = [
      'index' => 'blog',
      'type' => 'post',
      'body' => [
        'query' => [
          'match' => [
            'body' => 'máximo tamaño'
          ]
        ]
      ]
    ];
    $response = $this->searchCtrl->search($params);
    echo "\n\n SEARCH máximo tamaño \n"; $this->mostrar($response);

    echo $searchInfo;
  }


  public function mostrar( $datos ) {
    $pr = print_r( $datos, true );

    $pat = ['/^\s*[\(\)]?\s*\n/m', '/    /'];
    $sus = ['', '  '];
    echo preg_replace( $pat, $sus, $pr );
  }

} // END SearchView class
