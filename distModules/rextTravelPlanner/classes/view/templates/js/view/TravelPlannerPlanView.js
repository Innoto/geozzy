var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerPlanView = Backbone.View.extend({
  el: "#travelPlannerSec",
  planTemplate : false,
  dayTemplate: false,
  resourcePlanItemTemplate: false,
  planDays: false,
  parentTp : false,

  events: {

  },

  initialize: function( parentTp ) {
    var that = this;

    that.delegateEvents();
    that.parentTp = parentTp;
    that.dayTemplate = _.template( $('#dayTPTemplate').html() );
    that.resourcePlanItemTemplate = _.template( $('#resourcePlanItemTemplate').html() );
    that.planDays = 1 + that.parentTp.tpData.get('checkout').diff(that.parentTp.tpData.get('checkin'), 'days');

    that.render();
  },

  render: function() {
    var that = this;
    console.log('Difference is ', that.planDays , 'days');

    that.$('.travelPlannerPlanDaysContainer').html('');
    for (i = 0; i < that.planDays; i++) {
      that.$('.travelPlannerPlanDaysContainer').append( that.dayTemplate({ day: (i)}) );
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
  },
  addResourcesPlan: function (idResource, days){
    var that = this;
    $.each( days, function(i,d){
      that.addResourceToDay( idResource, d);
    });
  },
  addResourceToDay: function( idResource, day){
    var that = this;
    var resource = that.parentTp.resources.get(idResource);
    that.$('.plannerDay-'+day+' ol.dd-list').append( that.resourcePlanItemTemplate({ resource : resource.toJSON() }) );
  },
  resourceInPlan: function( idResource ){
    var that = this;
    var days = [];

    return days;
  }
});
