var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerDatesView = Backbone.View.extend({
  el: "#travelPlannerSec",
  datesTemplate : false,
  modalTemplate : false,
  parentTp : false,

  events: {

  },

  initialize: function( parentTp ) {
    var that = this;

    that.delegateEvents();
    that.parentTp = parentTp;


    that.initDatesInterface();
  },

  render: function() {
    var that = this;
  },

  initDatesInterface: function(){
    var that = this;

    that.datesTemplate = _.template( $('#datesTPTemplate').html() );
    that.$('.travelPlannerPlanHeader').html( that.datesTemplate() );

    var calDateFormat = that.parentTp.dateFormat;
    var calCheckIn = false;
    var calCheckOut = false;

    if( that.parentTp.tpData.get('checkin') === false || that.parentTp.tpData.get('checkout') === false ){
      calCheckIn = moment().add( 0, 'days' );
      calCheckOut = moment().add( 0, 'days' );
    }else{
      calCheckIn = that.parentTp.tpData.get('checkin');
      calCheckOut = that.parentTp.tpData.get('checkout');
    }


    $( '#checkTpDates' ).daterangepicker(
      {
        'showCustomRangeLabel': true,
        'startDate':  calCheckIn,
        'minDate':  calCheckIn,
        'endDate':  calCheckOut,
        'autoApply': true,
        'locale': {
          'format': calDateFormat,
          'firstDay': 1
        }
      },
      function( start, end, label ) {
        that.parentTp.tpData.set('checkin', start);
        that.parentTp.tpData.set('checkout', end);

        console.log( 'From: ' + that.parentTp.tpData.get('checkin').format( calDateFormat ) + ' to ' + that.parentTp.tpData.get('checkout').format( calDateFormat ) );
        console.log('initPlan DATESVIEW');
        that.parentTp.initPlan();
      }
    );
  }


});
