var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerPlanView = Backbone.View.extend({
  el: "#travelPlannerSec",
  planTemplate : false,
  dayTemplate: false,
  reourceItemDayTemplate: false,
  planDays: false,
  parentTp : false,

  events: {

  },

  initialize: function( parentTp ) {
    var that = this;

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
    that.$('.travelPlannerPlanDaysContainer').html('');
    for (i = 0; i < that.planDays; i++) {
      that.$('.travelPlannerPlanDaysContainer').append( that.dayTemplate({ day: (i+1)}) );
    }

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
  }

});
