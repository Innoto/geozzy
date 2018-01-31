<?php

class SearchController {

  private $searchService = null;
  private $indexName = 'resource';
  private $indexType = 'base';
  private $limit = 20;
  private $langAnalyzer = [
    'es' => 'spanish',
    'gl' => 'galician',
    'eu' => 'basque',
    'ca' => 'catalan',
    'pt' => 'portuguese',
    'en' => 'english',
    'fr' => 'french',
    'de' => 'german',
    'it' => 'italian',
    'da' => 'danish',
    'fi' => 'finnish',
    'el' => 'greek',
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
      $this->keysLang = array_keys($this->allLang);
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
      $properties = [
        'id' => [ 'type' => 'integer' ],
        'rTypeId' => [ 'type' => 'integer' ],
        'rTypeIdName' => [ 'type' => 'keyword' ], //only searchable by their exact value.
        'user' => [ 'type' => 'integer' ],
        'timeCreation' => [ 'type' => 'date' ],
        'timeLastUpdate' => [ 'type' => 'date' ],
        'location' => [ 'type' => 'geo_point' ],
      ];

      foreach( $this->keysLang as $langKey ) {
        $properties[ 'title_suggest_'.$langKey ] = [
          'type' => 'completion',
          'analyzer' => $this->langAnalyzer[ $langKey ]
        ];
      }

      $textFields = [ 'title', 'shortDescription', 'mediumDescription', 'content',
        'headKeywords', 'headDescription', 'headTitle', 'termsNames', 'urlAlias' ];
      foreach( $textFields as $name ) {
        foreach( $this->keysLang as $langKey ) {
          $properties[ $name.'_'.$langKey ] = [
            'type' => 'text',
            'analyzer' => $this->langAnalyzer[ $langKey ]
          ];
        }
      }


      // Create the index with mappings and settings now
      $params = [
        'index' => $this->indexName,
        'body' => [
          'mappings' => [
            $indexType => [
              'properties' => $properties
            ]
          ]
        ]
      ];

      // $this->mostrar($params);
      // die();
      $response = $this->searchService->indices()->create($params);


      $taxText = [];
      $taxModel = new ResourceTaxonomyAllModel();
      $taxList = $taxModel->listItems();
      if( is_object( $taxList ) ) {
        while( $taxObj = $taxList->fetch() ) {
          $resId = $taxObj->getter('resource');

          foreach( $this->keysLang as $langKey ) {
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

          foreach( $this->keysLang as $langKey ) {
            $title = $this->getValueTr( $resObj, 'title', $langKey);

            $base['title_suggest_'.$langKey] = $title;

            $base['title_'.$langKey] = $title;
            $base['shortDescription_'.$langKey] = $this->getValueTr( $resObj, 'shortDescription', $langKey);
            $base['mediumDescription_'.$langKey] = html_entity_decode( strip_tags( $this->getValueTr( $resObj, 'mediumDescription', $langKey) ) );
            $base['content_'.$langKey] = html_entity_decode( strip_tags( $this->getValueTr( $resObj, 'content', $langKey) ) );
            $base['headKeywords_'.$langKey] = $this->getValueTr( $resObj, 'headKeywords', $langKey);
            $base['headDescription_'.$langKey] = $this->getValueTr( $resObj, 'headDescription', $langKey);
            $base['headTitle_'.$langKey] = $this->getValueTr( $resObj, 'headTitle', $langKey);

            if( !empty( $taxText[$resId][$langKey] ) ) {
              $base['termsNames_'.$langKey] = $taxText[$resId][$langKey];
            }

            $base['urlAlias_'.$langKey] = $this->getValueTr( $resObj, 'urlAlias', $langKey);
          }

          $params = [
            'index' => $this->indexName,
            'type' => $indexType,
            'id' => $resId,
            'body' => $base
          ];

          $response = $this->searchService->index($params);
        } // while( $resObj = $resList->fetch() )
      }

      // $this->mostrar( $base );

      $searchInfo .= "\n\n createIndexBase $this->indexName FIN \n\n";
    }

    return $searchInfo;
  }

  public function getValueTr( $obj, $fieldName, $lang ) {
    $value = $obj->getter($fieldName.'_'.$lang);
    if( $lang !== $this->defLang && empty( $value ) ) {
      $value = $obj->getter($fieldName.'_'.$this->defLang);
    }

    return $value;
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
        // [ 'match' => [ 'lang' => $lang ] ],
        [ 'multi_match' => [
        'type' => 'most_fields',
          'query' => $text,
          'fields' => [
            'title_'.$this->actLang.'^4',
            'shortDescription_'.$this->actLang.'^3',
            'mediumDescription_'.$this->actLang.'^3',
            'termsNames_'.$this->actLang.'^2',
            'content_'.$this->actLang,
            'headKeywords_'.$this->actLang,
            'headDescription_'.$this->actLang,
            'headTitle_'.$this->actLang,
          ]
        ]]
      ];

      $params['body']['query'] = [
        'bool' => [
          'must' => $matchs
        ]
      ];

      // $this->mostrar($params);

      $searchInfo = $this->searchService->search($params);
      // echo "\n\n --- RESULTADOS: ".$searchInfo['hits']['total']." --- \n\n";
      if( !empty($searchInfo['hits']) ) {
        $response = $searchInfo['hits'];
      }
    }

    return $response;
  }



  public function search_around( $text, $lang = false ) {
    return $this->search_around_inType( $this->indexType, $text, $lang );
  }

  public function search_around_inType( $indexType, $text, $lang = false ) {
    $response = false;

    if( $this->searchService ) {

      if( empty( $lang ) ) {
        $lang = $this->actLang;
      }

      if( !empty( $text['limit'] ) ) {
        $this->limit = $text['limit'];
      }

      $params = [
        'index' => $this->indexName,
        'type' => $indexType,
        'size' => $this->limit,
        'body' => [
          'query' => [
            'bool' => [
              'must' => []
            ]
          ],
          'sort' => [
            '_geo_distance' => [
              'location' => $text['geolocation']['location'],
              'order' => 'asc'
            ]
          ]
        ]
      ];

      if( !empty( $text['geolocation']['distance'] ) ) {
        $params['body']['query']['bool']['must'][] = [
          'geo_distance' => [
            'distance' => $text['geolocation']['distance'],
            'location' => $text['geolocation']['location']
          ]
        ];
      }
      else{
        $params['body']['query']['bool']['must'][] = [
          'geo_distance' => [
            'location' => $text['geolocation']['location']
          ]
        ];
      }

      if( !empty( $text['rTypeIdName'] ) ) {
        $params['body']['query']['bool']['must'][] = [
          'terms' => [ 'rTypeIdName' => $text['rTypeIdName'] ]
        ];
      }

      if( !empty( $text['fields'] ) ) {
        $params['body']['_source'] = $text['fields'];
      }

      // $this->mostrar($params);
      $searchInfo = $this->searchService->search($params);
      // echo "\n\n --- RESULTADOS: ".$searchInfo['hits']['total']." --- \n\n";
      if( !empty($searchInfo['hits']) ) {
        $responseAll = $searchInfo['hits'];
        $response = array_column( $responseAll['hits'], '_source' );  //  Only fields resource base
      }
    }

    return $response;
  }


  public function getJsonSuggest( $busca ) {
    $result = [
      'query' => $busca,
      'suggestions' => []
    ];

    $response = $this->getInfoSuggest( $busca );

    if( !empty($response['search_suggest'][0]['options']) && count($response['search_suggest'][0]['options']) > 0 ) {
      foreach( $response['search_suggest'][0]['options'] as $res ) {
        $result['suggestions'][] = [
          'value' => $res['_source']['title_'.$this->actLang],
          'data' => [
            'id' => $res['_source']['id'],
            'url' => '/'.$this->actLang.$res['_source']['urlAlias_'.$this->actLang]
          ]
        ];
      }
    }

    return json_encode( $result );
  }

  public function getInfoSuggest( $busca ) {
    $response = false;

    $params = [
      'index' => $this->indexName,
      'body' => [
        'search_suggest' => [
          'text' => $busca,
          'completion' => [
            'size' => 10,
            'fuzzy' => [ 'fuzziness' => 'AUTO' ],
            'field' => 'title_suggest_'.$this->actLang
          ]
        ]
      ]
    ];

    // $this->mostrar($params);

    $response = $this->searchService->suggest($params);

    return $response;
  }


  public function getInfoSuggestSearch( $busca ) {
    $response = false;

    // {
    //   "query" : {
    //     "match": {
    //       "message": "tring out Elasticsearch"
    //     }
    //   },
    //   "suggest" : {
    //     "my-suggestion" : {
    //       "text" : "trying out Elasticsearch",
    //       "term" : {
    //         "field" : "message"
    //       }
    //     }
    //   }
    // }

    $params = [
      'index' => $this->indexName,
      'type' => $this->indexType,
      'body' => [
        'query' => [
          'match' => [
            'title_gl' => $busca
          ]
        ]
        // ,
        // 'suggest' => [
        //   'text' => $busca,
        //   'search_suggest_1' => [
        //     'term' => [
        //       'field' => 'title_gl'
        //     ]
        //   ]
        // ]
      ]
    ];

    $this->mostrar($params);

    $response = $this->searchService->search($params);

    $this->mostrar($response);
    die();

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



  public function mostrar( $datos ) {
    $pr = print_r( $datos, true );

    $pat = ['/^\s*[\(\)]?\s*\n/m', '/    /', '/ => Array/'];
    $sus = ['', '  ', ''];
    echo preg_replace( $pat, $sus, $pr );
  }
} // END SearchController class
