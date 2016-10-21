var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerPlanView = Backbone.View.extend({

  planTemplate : false,
  dayTemplate: false,
  reourceItemDayTemplate: false,
  planDays: false,
  parentTp : false,

  events: {

  },

  initialize: function( parentTp ) {
    var that = this;

    that.el = "#travelPlannerSec";
    that.$el = $(that.el);
    that.delegateEvents();
    that.parentTp = parentTp;


    that.initPlanInterface();
  },

  render: function() {
    var that = this;
  },

  initPlanInterface: function(){
    var that = this;

    that.planDays = 1 + that.parentTp.tpData.get('checkout').diff(that.parentTp.tpData.get('checkin'), 'days');
    console.log('Difference is ', that.planDays , 'days');
    that.dayTemplate = _.template( $('#dayTPTemplate').html() );
    that.$el.find('.travelPlannerPlanDaysContainer').html('');
    for (i = 0; i < that.planDays; i++) {
      that.$el.find('.travelPlannerPlanDaysContainer').append( that.dayTemplate({ day: (i+1)}) );
    }
/*
    that.datesTemplate = _.template( $('#datesTPTemplate').html() );
    that.$el.find('.travelPlannerPlanHeader').html( that.datesTemplate() );

*/
    $('.gzznestable.dd').nestable({
      'maxDepth': 1,
      'dragClass': "gzznestable dd-dragel",
      callback: function(l, e) {
        $('.gzznestable').each(function( index ) {
          console.log('DAY'+ (index+1))
          console.log($(this).nestable('serialize'));
        });
      }
    });

    that.el = "#travelPlannerSec";
    that.$el = $(that.el);
    that.delegateEvents();
  }

});
