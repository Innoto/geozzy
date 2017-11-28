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
    that.$('.travelPlannerDateBar').html( that.datesTemplate() );

    var calDateFormat = that.parentTp.dateFormat;
    var calCheckIn = false;
    var calCheckOut = false;

    if( that.parentTp.tpData.get('checkin') === null || that.parentTp.tpData.get('checkout') === null ){
      calCheckIn = moment().add( 0, 'days' );
      calCheckOut = moment().add( 0, 'days' );
    }else{
      calCheckIn = that.parentTp.momentDate( that.parentTp.tpData.get('checkin') );
      calCheckOut = that.parentTp.momentDate( that.parentTp.tpData.get('checkout') );
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
        that.parentTp.tpData.set('checkin', start.format( that.parentTp.timeServerFormat ) );
        that.parentTp.tpData.set('checkout', end. format( that.parentTp.timeServerFormat ) );

        that.parentTp.initPlan();
        that.parentTp.travelPlannerPlanView.fromHtmlToModel();
        that.parentTp.travelPlannerInterfaceView.drawFilterDay();
      }
    );
  }


});
