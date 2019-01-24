/**
 *  RExtReservation Controller
 */
var geozzy = geozzy || {};

geozzy.rExtReservationController = geozzy.rExtReservationController || {
  init: function init() {
    //cogumelo.log('init rExtReservationController');

    geozzy.rExtReservationInfo.values = {
      checkin : moment().add( 1, 'days' ),
      checkout : moment().add( 3, 'days' ),
      adults : '2',
      rooms : '1'
    };
    $field = $( geozzy.rExtReservationInfo.idAdults );
    $field.val( geozzy.rExtReservationInfo.values.adults );
    $field = $( geozzy.rExtReservationInfo.idRooms );
    $field.val( geozzy.rExtReservationInfo.values.rooms );
    this.initCalendar();

    $( geozzy.rExtReservationInfo.idLink ).on( 'mouseenter', function() {
      geozzy.rExtReservationController.updateLink();
    });

    $( geozzy.rExtReservationInfo.idRooms ).on( 'change', function() {
      geozzy.rExtReservationInfo.values.rooms = this.value;
      geozzy.rExtReservationController.updateLink();
    });

    $( geozzy.rExtReservationInfo.idAdults ).on( 'change', function() {
      geozzy.rExtReservationInfo.values.adults = this.value;
      geozzy.rExtReservationController.updateLink();
    });

    this.updateLink();
  },
  updateLink: function updateLink() {
    urlDateFormat = geozzy.rExtReservationInfo.urlDateFormat;
    calDateFormat = geozzy.rExtReservationInfo.calDateFormat;

    $field = $( geozzy.rExtReservationInfo.idAdults );
    geozzy.rExtReservationInfo.values.adults = $field.val();

    $field = $( geozzy.rExtReservationInfo.idRooms );
    geozzy.rExtReservationInfo.values.rooms = $field.val();

    values = geozzy.rExtReservationInfo.values;

    link = geozzy.rExtReservationInfo.srcUrl;
    link = link.replace( '<$checkin>', values.checkin.format( urlDateFormat ) );
    link = link.replace( '<$checkout>', values.checkout.format( urlDateFormat ) );
    link = link.replace( '<$adults>', values.adults );
    link = link.replace( '<$rooms>', values.rooms );
    $( geozzy.rExtReservationInfo.idLink ).attr( 'href', link );
  },
  initCalendar: function initCalendar() {
    calDateFormat = geozzy.rExtReservationInfo.calDateFormat;
    $( geozzy.rExtReservationInfo.idCal ).daterangepicker(
      {
        'parentEl': '.rextReservation',
        'showCustomRangeLabel': false,
        'startDate': geozzy.rExtReservationInfo.values.checkin.format( calDateFormat ),
        'minDate': geozzy.rExtReservationInfo.values.checkin.format( calDateFormat ), /* Evitar poner fecha anterior al día actual */
        'endDate': geozzy.rExtReservationInfo.values.checkout.format( calDateFormat ),
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
        geozzy.rExtReservationInfo.values.checkin = start;
        geozzy.rExtReservationInfo.values.checkout = end;
        geozzy.rExtReservationController.updateLink();
        cogumelo.log( 'From: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') );
      }
    );
  } // initCalendar
};

$(document).ready(function(){
  geozzy.rExtReservationController.init();
});
