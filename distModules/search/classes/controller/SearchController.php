<?php

class SearchController {

  private $searchService = null;
  private $indexName = 'resource';
  private $indexType = 'base';
  private $limit = 20;
  private $langAnalyzer = [
    'es' => 'Spanish',
    'gl' => 'Galician',
    'eu' => 'Basque',
    'ca' => 'Catalan',
    'pt' => 'Portuguese',
    'en' => 'English',
    'fr' => 'French',
    'de' => 'German',
    'it' => 'Italian',
    'da' => 'Danish',
    'fi' => 'Finnish',
    'el' => 'Greek',
  ];


  public function __construct() {
    $setupConf = Cogumelo::getSetupValue('mod:search');

    if( !empty( $setupConf['elasticsearch'] ) ) {
      $hosts = [
        $setupConf['elasticsearch']
      ];

      $this->searchService = Elasticsearch\ClientBuilder::create()->setHosts( $hosts )->build();

      if( empty( $setupConf['indexName'] ) ) {
        $this->indexName = Cogumelo::getSetupValue('db:name').'_resources_index';
      }
      else {
        $this->indexName = $setupConf['indexName'];
      }

      if( !empty( $setupConf['indexType'] ) ) {
        $this->indexType = $setupConf['indexType'];
      }

      if( !empty( $setupConf['limit'] ) ) {
        $this->limit = $setupConf['limit'];
      }

      global $C_LANG; // Idioma actual, cogido de la url
      $this->actLang = $C_LANG;
      $this->defLang = Cogumelo::getSetupValue('lang:default');
      $this->allLang = Cogumelo::getSetupValue('lang:available');
    }
  }

  public function createIndex( $indexType = false ) {
    $searchInfo = false;

    if( $this->searchService ) {
      if( !$indexType ) {
        $indexType = $this->indexType;
      }

      $searchInfo = "\n\n createIndexBase $this->indexName \n\n";

      $params = ['index' => $this->indexName];
      if( $this->searchService->indices()->exists($params) ) {
        $response = $this->searchService->indices()->delete($params);
      }


      // $typeTextIndex = [ 'type' => 'text', 'copy_to' => 'searchAllText' ];
      $typeTextIndex = [ 'type' => 'text', 'analyzer' => 'spanish' ];
      $params = [
        'index' => $this->indexName,
        'body' => [
          'mappings' => [
            $indexType => [
              'properties' => [
                'id' => [ 'type' => 'integer' ],
                'lang' => [ 'type' => 'keyword' ], //only searchable by their exact value.
                'rTypeId' => [ 'type' => 'integer' ],
                'rTypeIdName' => [ 'type' => 'keyword' ], //only searchable by their exact value.
                'user' => [ 'type' => 'integer' ],
                'timeCreation' => [ 'type' => 'date' ],
                'timeLastUpdate' => [ 'type' => 'date' ],
                'title' => $typeTextIndex,
                'title_suggest' => [
                  'type' => 'completion',
                  'analyzer' => 'spanish'
                ],
                'shortDescription' => $typeTextIndex,
                'mediumDescription' => $typeTextIndex,
                'content' => $typeTextIndex,
                'termsNames' => $typeTextIndex,
                'headKeywords' => $typeTextIndex,
                'headDescription' => $typeTextIndex,
                'headTitle' => $typeTextIndex,
                'urlAlias' => [ 'type' => 'text' ],
                'location' => [ 'type' => 'geo_point' ],
                // 'searchAllText' => [ 'type' => 'text' ], // Combined text fields
              ]
            ]
          ]
        ]
      ];
      // Create the index with mappings and settings now
      $response = $this->searchService->indices()->create($params);


      $taxText = [];
      $taxModel = new ResourceTaxonomyAllModel();
      $taxList = $taxModel->listItems();
      if( is_object( $taxList ) ) {
        while( $taxObj = $taxList->fetch() ) {
          $resId = $taxObj->getter('resource');

          foreach( array_keys($this->allLang) as $langKey ) {
            $termName = $this->getValueTr( $taxObj, 'name', $langKey);
            if( !empty( $termName ) ) {
              $taxText[$resId][$langKey] = empty($taxText[$resId][$langKey]) ? $termName : $taxText[$resId][$langKey].', '.$termName;
            }
          }
        }
      }


      $resModel = new ResourceViewModel();
      $filters = [ 'published' => 1 ];

      $indexFilters = Cogumelo::getSetupValue('mod:search:indexFilters');
      if( !empty($indexFilters['rTypeIdName']) ) {
        $filters['inRtypeIdName'] = is_array($indexFilters['rTypeIdName']) ? $indexFilters['rTypeIdName'] : [ $indexFilters['rTypeIdName'] ];
      }
      $resList = $resModel->listItems([ 'filters' => $filters ]);
      if( is_object( $resList ) ) {
        Cogumelo::load('coreModel/DBUtils.php');
        while( $resObj = $resList->fetch() ) {
          $resId = $resObj->getter('id');
          $searchInfo .= "\n Resource: ".$resId;
          $base = [
            'id' => $resId,
            'rTypeId' => $resObj->getter('rTypeId'),
            'rTypeIdName' => $resObj->getter('rTypeIdName'),
            'user' => $resObj->getter('user'),
          ];
          if( $timeCreation = $resObj->getter('timeCreation') ) {
            $base['timeCreation'] = strtr( $timeCreation, ' ', 'T').'Z';
          }
          if( $timeLastUpdate = $resObj->getter('timeLastUpdate') ) {
            $base['timeLastUpdate'] = strtr( $timeLastUpdate, ' ', 'T').'Z';
          }
          if( $loc = $resObj->getter('loc') ) {
            $geoLocation = DBUtils::decodeGeometry( $loc );
            // $base['location'] = ''.$geoLocation['data'][0].','.$geoLocation['data'][1];
            $base['location'] = [
              'lon' => $geoLocation['data'][1],
              'lat' => $geoLocation['data'][0],
            ];
          }

          foreach( array_keys($this->allLang) as $langKey ) {

            $base['lang'] = $langKey;
            $base['title'] = $this->getValueTr( $resObj, 'title', $langKey);
            $base['title_suggest'] = $base['title'];
            $base['shortDescription'] = $this->getValueTr( $resObj, 'shortDescription', $langKey);
            $base['mediumDescription'] = html_entity_decode( strip_tags( $this->getValueTr( $resObj, 'mediumDescription', $langKey) ) );
            $base['content'] = html_entity_decode( strip_tags( $this->getValueTr( $resObj, 'content', $langKey) ) );
            $base['headKeywords'] = $this->getValueTr( $resObj, 'headKeywords', $langKey);
            $base['headDescription'] = $this->getValueTr( $resObj, 'headDescription', $langKey);
            $base['headTitle'] = $this->getValueTr( $resObj, 'headTitle', $langKey);
            $base['urlAlias'] = $this->getValueTr( $resObj, 'urlAlias', $langKey);
            if( !empty( $taxText[$resId][$langKey] ) ) {
              $base['termsNames'] = $taxText[$resId][$langKey];
            }
            else {
              unset($base['termsNames']);
            }

            $params = [
              'index' => $this->indexName,
              'type' => $indexType,
              'id' => $resId.'_'.$langKey,
              'body' => $base
            ];

            $response = $this->searchService->index($params);
          }

        }
      }


      $searchInfo .= "\n\n createIndexBase $this->indexName FIN \n\n";
    }

    return $searchInfo;
  }

  public function search( $text, $lang = false ) {

    return $this->searchInType( $this->indexType, $text, $lang );
  }

  public function searchInType( $indexType, $text, $lang = false ) {
    $response = false;

    if( $this->searchService ) {

      if( empty($lang) ) {
        $lang = $this->actLang;
      }

      $params = [
        'index' => $this->indexName,
        'type' => $indexType,
        'size' => $this->limit,
        'body' => []
      ];

      $matchs = [
        [ 'match' => [ 'lang' => $lang ] ],
        [ 'multi_match' => [
        'type' => 'most_fields',
          'query' => $text,
          'fields' => [
            'title^4',
            'termsNames^2',
            'shortDescription^3',
            'mediumDescription^3',
            'content',
            'headKeywords',
            'headDescription',
            'headTitle',
          ]
        ]]
      ];

      $params['body']['query'] = [
        'bool' => [
          'must' => $matchs
        ]
      ];

      $searchInfo = $this->searchService->search($params);
      // echo "\n\n --- RESULTADOS: ".$searchInfo['hits']['total']." --- \n\n";
      if( !empty($searchInfo['hits']) ) {
        $response = $searchInfo['hits'];
      }
    }

    return $response;
  }


  public function getValueTr( $obj, $fieldName, $lang ) {
    $value = $obj->getter($fieldName.'_'.$lang);
    if( $lang !== $this->defLang && empty( $value ) ) {
      $value = $obj->getter($fieldName.'_'.$this->defLang);
    }

    return $value;
  }





  public function showInfoSuggest() {
    $response = false;

    $params = [
      'index' => $this->indexName,
      'body' => [
        'search_suggest' => [
          'text' => $_GET['s'],
          'completion' => [
            'field' => 'title_suggest'
          ]
        ]
      ]
    ];

    $response = $this->searchService->suggest($params);

    return $response;
  }

  //
  //
  //
  // public function buscamos_2() {
  //   header('Content-Type: text/plain');
  //
  //   $params = [
  //     'index' => $this->indexName,
  //     'type' => $this->indexType,
  //     'body' => [
  //       'query' => []
  //     ]
  //   ];
  //
  //   $matchs = [
  //     [ 'match' => [ 'lang' => $this->actLang ] ]
  //   ];
  //
  //   if( !empty($_GET['s']) ) {
  //     $matchs[] = [ 'match' => [ 'searchAllText' => $_GET['s'] ] ];
  //   }
  //   if( !empty($_GET['r']) ) {
  //     $matchs[] = [ 'match' => [ 'rTypeIdName' => $_GET['r'] ] ];
  //   }
  //
  //   $params['body']['query'] = [
  //     'bool' => [
  //       'must' => $matchs
  //     ]
  //   ];
  //   $this->mostrar($params);
  //
  //
  //   $response = $this->searchService->search($params);
  //   echo "\n\n --- RESULTADOS: ".$response['hits']['total']." --- \n\n";
  //   $this->mostrar($response);
  // }
  //
  // public function buscamos_1() {
  //   header('Content-Type: text/plain');
  //
  //   $params = [
  //     'index' => $this->indexName,
  //     'type' => $this->indexType,
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
  //   $response = $this->searchService->search($params);
  //   echo "\n\n --- RESULTADOS: ".$response['hits']['total']." --- \n\n";
  //   $this->mostrar($response);
  // }
  //
  // public function showInfo() {
  //   $searchInfo = "\n\nshowInfo FIN\n\n";
  //
  //   header('Content-Type: text/plain');
  //
  //   $params = [ 'index' => $this->indexName, 'type' => $this->indexType, 'id' => '22_gl' ];
  //   $response = $this->searchService->get($params);
  //   echo "\n\n --- GET 22 \n"; $this->mostrar($response);
  //
  //
  //   $params = [
  //     'index' => $this->indexName,
  //     'type' => $this->indexType,
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
  //   $response = $this->searchService->search($params);
  //   echo "\n\n --- SEARCH GEO \n"; $this->mostrar($response);
  //
  //
  //   $params = [
  //     'index' => $this->indexName,
  //     'type' => $this->indexType,
  //     'body' => [
  //       'query' => [
  //         'match' => [
  //           'searchAllText' => 'deixar'
  //         ]
  //       ]
  //     ]
  //   ];
  //   $response = $this->searchService->search($params);
  //   echo "\n\n --- SEARCH vasto \n"; $this->mostrar($response);
  //
  //
  //   $params = [
  //     'index' => $this->indexName,
  //     'type' => $this->indexType,
  //     'body' => [
  //       'query' => [
  //         'match' => [
  //           'rTypeIdName' => 'rtypeStoryStep'
  //         ]
  //       ]
  //     ]
  //   ];
  //   $response = $this->searchService->search($params);
  //   echo "\n\n --- SEARCH rtypeStoryStep \n"; $this->mostrar($response);
  //
  //
  //   $params = [ 'index' => $this->indexName ];
  //   $response = $this->searchService->indices()->getSettings($params);
  //   echo "\n\n --- getSettings \n"; $this->mostrar($response);
  //
  //
  //   echo $searchInfo;
  // }
  //
  // public function createIndexDemo() {
  //   $searchInfo = "\n\nFIN\n\n";
  //
  //   header('Content-Type: text/plain');
  //
  //   $params = ['index' => 'blog'];
  //   $response = $this->searchService->indices()->delete($params);
  //
  //   $params = [
  //     'index' => 'blog',
  //     'type' => 'user',
  //     'id' => 'dilbert',
  //     'body' => [
  //       'name' => 'Dilbert Brown',
  //     ]
  //   ];
  //   $response = $this->searchService->index($params);
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
  //   $response = $this->searchService->index($params);
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
  //   $response = $this->searchService->index($params);
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
  //   $response = $this->searchService->index($params);
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
  //   // $response = $this->searchService->get($params);
  //   // echo "\n\n GET dilbert \n"; $this->mostrar($response);
  //
  //   // $params = [
  //   //   'index' => 'blog',
  //   //   'type' => 'post',
  //   //   'id' => '3',
  //   // ];
  //   // $response = $this->searchService->get($params);
  //   // echo "\n\n GET 3 \n"; $this->mostrar($response);
  //
  //   // $params = [ 'index' => 'blog' ];
  //   // $response = $this->searchService->indices()->getSettings($params);
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
  //   $response = $this->searchService->search($params);
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
  //   $response = $this->searchService->search($params);
  //   echo "\n\n SEARCH máximo tamaño \n"; $this->mostrar($response);
  //
  //   echo $searchInfo;
  // }
  //
  //
  //
} // END SearchController class
