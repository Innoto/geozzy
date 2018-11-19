/**
 *  RExtAccommodationReserve Controller
 */
var geozzy = geozzy || {};

geozzy.rExtAccommodationReserveController = geozzy.rExtAccommodationReserveController || {
  init: function init() {
    cogumelo.log('init rExtAccommodationReserveController');

    geozzy.rExtAccommodationReserveInfo.values = {
      checkin : moment().add( 1, 'days' ),
      checkout : moment().add( 3, 'days' ),
      adults : '2',
      rooms : '1'
    };

    this.initCalendar();

    this.updateLink();
  },
  updateLink: function updateLink() {
    urlDateFormat=geozzy.rExtAccommodationReserveInfo.urlDateFormat;
    calDateFormat=geozzy.rExtAccommodationReserveInfo.calDateFormat;

    values = geozzy.rExtAccommodationReserveInfo.values;

    link = geozzy.rExtAccommodationReserveInfo.srcUrl;
    link = link.replace( '<$checkin>', values.checkin.format( urlDateFormat ) );
    link = link.replace( '<$checkout>', values.checkout.format( urlDateFormat ) );
    $( geozzy.rExtAccommodationReserveInfo.idLink ).attr( 'href', link );

    /*show = values.checkin.format( calDateFormat )+' - '+values.checkout.format( calDateFormat );
    $cal = $( geozzy.rExtAccommodationReserveInfo.idCal );
    if ( $cal.is( 'input' ) ) {
      $cal.val( show );
    }
    else {
      $cal.html( show );
    }*/
  },
  initCalendar: function initCalendar() {
    calDateFormat=geozzy.rExtAccommodationReserveInfo.calDateFormat;
    $( geozzy.rExtAccommodationReserveInfo.idCal ).daterangepicker(
      {
        'showCustomRangeLabel': false,
        'startDate': geozzy.rExtAccommodationReserveInfo.values.checkin.format( calDateFormat ),
        'minDate': geozzy.rExtAccommodationReserveInfo.values.checkin.format( calDateFormat ), /* Evitar poner fecha anterior al día actual */
        'endDate': geozzy.rExtAccommodationReserveInfo.values.checkout.format( calDateFormat ),
        'autoApply': true,
        'locale': {
          'format': calDateFormat,
          'firstDay': 1
        }
        /*
        'locale': {
          'separator': ' - ',
          'applyLabel': 'Apply',
          'cancelLabel': 'Cancel',
          'fromLabel': 'From',
          'toLabel': 'To',
          'customRangeLabel': 'Custom',
          'weekLabel': 'W',
          'daysOfWeek': [ 'Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa' ],
          'monthNames': [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ],
          'firstDay': 1
        },
        */
      },
      function( start, end, label ) {
        geozzy.rExtAccommodationReserveInfo.values.checkin = start;
        geozzy.rExtAccommodationReserveInfo.values.checkout = end;
        geozzy.rExtAccommodationReserveController.updateLink();
        cogumelo.log( 'From: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') );
      }
    );

  } // initCalendar
};

$(document).ready(function(){
  geozzy.rExtAccommodationReserveController.init();
});
