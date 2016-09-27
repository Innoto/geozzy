<?php

// Key: Channel IdName

cogumelo::setSetupValue( 'mod:rextAccommodationReserve:channelOptions:booking.com', [
  'type' => 'srcLink',
  'pattern' => 'http://www.booking.com/hotel/<$langName>/<$idRelate>.html?checkin=<$checkin>;checkout=<$checkout>;group_adults=<$adults>;no_rooms=<$rooms>#availability_target',
  'patternDateFormat' => 'YYYY-MM-DD',
  'params' => [
    // 'admin' => [ 'idRelate' ],
    // 'internal' => [ 'langName' ],
    'public' => [ 'checkin', 'checkout', 'adults', 'rooms' ]
  ],
  'template' => [
    'public' => 'booking-com-public.tpl',
    'scripts' => [ 'js/booking-com-public.js' ],
    // 'styles' => []
  ]
]);
// http://www.booking.com/hotel/es/hesperia-coruna.html?checkin=2016-11-15;checkout=2016-11-16;group_adults=4;no_rooms=2


cogumelo::setSetupValue( 'mod:rextAccommodationReserve:channelOptions:netubi.com', [
  'type' => 'srcLink',
  'pattern' => 'https://www.netubi.com/m/<$langName>/<$idRelate>/1/<$checkin>/<$checkout>',
  'patternDateFormat' => 'DD-MM-YYYY',
  'params' => [
    // 'admin' => [ 'idRelate' ],
    // 'internal' => [ 'langName' ],
    'public' => [ 'checkin', 'checkout' ]
  ],
  'template' => [
    'public' => 'netubi-com-public.tpl',
    'scripts' => [ 'js/netubi-com-public.js' ],
  ]
]);
// https://www.netubi.com/m/es/hotel-abeiras/1/05-08-2016/06-08-2016


cogumelo::setSetupValue( 'mod:rextAccommodationReserve:channelOptions:thebookingbutton.com', [
  'type' => 'srcIframe',
  'pattern' => 'https://app.thebookingbutton.com/properties/<$idRelate>/widget?locale=<$langName>&number_of_days=14',
  'patternDateFormat' => 'YYYY-MM-DD',
  'params' => [
    // 'admin' => [ 'idRelate' ],
    // 'internal' => [ 'langName' ],
    'public' => []
  ],
  'template' => [
    'public' => 'thebookingbutton-com-public.tpl'
  ]
]);
/*
  <iframe src="https://app.thebookingbutton.com/properties/campingbielsadirect/widget?
  locale=es&number_of_days=14&start_date=2016-09-22" height="500" width="100%"
  frameborder="0" scrolling="yes" allowtransparency="true"></iframe>
*/


/*
//
// PELIGRO: Retirado porque causa conflictos en javascript !!!
//
cogumelo::setSetupValue( 'mod:rextAccommodationReserve:channelOptions:ruralgest.net', [
  'type' => 'srcScript',
  'pattern' => 'http://www.ruralgest.net/scr/modulos/recursos_ext/js_ext/ext_v001.php?id_casa=<$idRelate>&amp;id_idioma=0&amp;espacio=2&amp;n_c=1',
  'patternDateFormat' => 'YYYY-MM-DD',
  'params' => [
    // 'admin' => [ 'idRelate' ],
    // 'internal' => [],
    'public' => []
  ],
  'template' => [
    'public' => 'ruralgest-net-public.tpl'
  ]
]);
*/
/*
<script src="http://www.ruralgest.net/scr/modulos/recursos_ext/js_ext/ext_v001.php?id_casa=575983&amp;id_idioma=0&amp;espacio=2&amp;n_c=1"></script>
*/
