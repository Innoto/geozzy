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

    $field = $( geozzy.rExtAccommodationReserveInfo.idAdults );
    $field.val( geozzy.rExtAccommodationReserveInfo.values.adults );

    $field = $( geozzy.rExtAccommodationReserveInfo.idRooms );
    $field.val( geozzy.rExtAccommodationReserveInfo.values.rooms );

    this.initCalendar();

    $( geozzy.rExtAccommodationReserveInfo.idLink ).on( 'mouseenter', function() {
      geozzy.rExtAccommodationReserveController.updateLink();
    });

    $( geozzy.rExtAccommodationReserveInfo.idRooms ).on( 'change', function() {
      geozzy.rExtAccommodationReserveInfo.values.rooms = this.value;
      geozzy.rExtAccommodationReserveController.updateLink();
    });

    $( geozzy.rExtAccommodationReserveInfo.idAdults ).on( 'change', function() {
      geozzy.rExtAccommodationReserveInfo.values.adults = this.value;
      geozzy.rExtAccommodationReserveController.updateLink();
    });

    this.updateLink();
  },
  updateLink: function updateLink() {
    urlDateFormat = geozzy.rExtAccommodationReserveInfo.urlDateFormat;
    calDateFormat = geozzy.rExtAccommodationReserveInfo.calDateFormat;

    $field = $( geozzy.rExtAccommodationReserveInfo.idAdults );
    geozzy.rExtAccommodationReserveInfo.values.adults = $field.val();

    $field = $( geozzy.rExtAccommodationReserveInfo.idRooms );
    geozzy.rExtAccommodationReserveInfo.values.rooms = $field.val();

    values = geozzy.rExtAccommodationReserveInfo.values;

    link = geozzy.rExtAccommodationReserveInfo.srcUrl;
    link = link.replace( '<$checkin>', values.checkin.format( urlDateFormat ) );
    link = link.replace( '<$checkout>', values.checkout.format( urlDateFormat ) );
    link = link.replace( '<$adults>', values.adults );
    link = link.replace( '<$rooms>', values.rooms );
    $( geozzy.rExtAccommodationReserveInfo.idLink ).attr( 'href', link );
  },
  initCalendar: function initCalendar() {
    calDateFormat = geozzy.rExtAccommodationReserveInfo.calDateFormat;
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
        },

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
