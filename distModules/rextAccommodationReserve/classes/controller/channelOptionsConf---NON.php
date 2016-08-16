<?php

// Array key: Channel IdName
$channelOptions = [];


$channelOptions['booking.com'] = [
  'type' => 'srcLink',
  'pattern' => 'http://www.booking.com/hotel/<$langName>/<$idRelate>.html?checkin=<$checkin>;checkout=<$checkout>;group_adults=<$adults>;no_rooms=<$rooms>',
  'dateFormat' => 'Y-m-d',
  'params' => [
    // 'admin' => [ 'idRelate' ],
    'internal' => [ 'langName' ],
    'public' => [ 'checkin', 'checkout', 'adults', 'rooms' ]
  ],
  'templates' => [
    // 'admin' => 'booking-com-admin.tpl',
    'public' => 'booking-com-public.tpl'
  ]
];
// http://www.booking.com/hotel/es/hesperia-coruna.html?checkin=2016-11-15;checkout=2016-11-16;group_adults=4;no_rooms=2


$channelOptions['netubi.com'] = [
  'type' => 'srcLink',
  'pattern' => 'https://www.netubi.com/m/<$langName>/<$idRelate>/1/<$checkin>/<$checkout>',
  'patternDateFormat' => 'd-m-Y',
  'params' => [
    // 'admin' => [ 'idRelate' ],
    'internal' => [ 'langName' ],
    'public' => [ 'checkin', 'checkout' ]
  ],
  'templates' => [
    // 'admin' => 'netubi-com-admin.tpl',
    'public' => 'netubi-com-public.tpl'
  ]
];
// https://www.netubi.com/m/es/hotel-abeiras/1/05-08-2016/06-08-2016


$channelOptions['thebookingbutton.com'] = [
  'type' => 'srcIframe',
  'pattern' => 'https://app.thebookingbutton.com/properties/<$idRelate>/widget?locale=<$langName>&number_of_days=14&start_date=<$checkin>',
  'dateFormat' => 'Y-m-d',
  'params' => [
    // 'admin' => [ 'idRelate' ],
    'internal' => [ 'langName' ],
    'public' => [ 'checkin' ]
  ],
  'templates' => [
    // 'admin' => 'thebookingbutton-com-admin.tpl',
    'public' => 'thebookingbutton-com-public.tpl'
  ]
];
/*
  <iframe src="https://app.thebookingbutton.com/properties/campingbielsadirect/widget?
  locale=es&number_of_days=14&start_date=2016-09-22" height="500" width="100%"
  frameborder="0" scrolling="yes" allowtransparency="true"></iframe>
*/


$channelOptions['ruralgest.net'] = [
  'type' => 'srcScript',
  'pattern' => 'http://www.ruralgest.net/scr/modulos/recursos_ext/js_ext/ext_v001.php?id_casa=<$idRelate>&amp;id_idioma=0&amp;espacio=2&amp;n_c=1',
  'dateFormat' => 'Y-m-d',
  'params' => [
    //'admin' => [ 'idRelate' ],
    'internal' => [],
    'public' => []
  ],
  'templates' => [
    // 'admin' => 'ruralgest-net-admin.tpl',
    'public' => 'ruralgest-net-public.tpl'
  ]
];
// <script src="http://www.ruralgest.net/scr/modulos/recursos_ext/js_ext/ext_v001.php?id_casa=575983&amp;id_idioma=0&amp;espacio=2&amp;n_c=1"></script>

