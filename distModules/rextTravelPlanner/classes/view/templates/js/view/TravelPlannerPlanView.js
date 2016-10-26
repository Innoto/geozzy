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
    'click .travelPlannerPlan .plannerDay .dd-item .btnDelete': 'removeResourceToDay'
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
    var checkin = moment(that.parentTp.tpData.get('checkin'));

    for (i = 0; i < that.planDays; i++) {
      var day = {
        id: i,
        date: checkin.format('LL'),
        dayName: checkin.format('ddd'),
        day: checkin.format('DD'),
        month: checkin.format('MMM'),
        inPlan: false
      };

      checkin.add(1, 'days');
      that.$('.travelPlannerPlanDaysContainer').append( that.dayTemplate({ day: day }) );
    }

    $('.gzznestable.dd').nestable({
      'maxDepth': 1,
      'dragClass': "gzznestable dd-dragel",
      callback: function(l, e) {

$('.gzznestable').each(function( index ) {
  console.log('DAY'+ (index))
  console.log($(this).nestable('serialize'));
});

        that.fromHtmlToModel();
      }
    });
  },
  addResourcesPlan: function (idResource, days){
    var that = this;
    $.each( days, function(i,d){
      that.addResourceToDay( idResource, d);
    });
    that.fromHtmlToModel();
  },
  addResourceToDay: function( idResource, day){
    var that = this;
    var resource = that.parentTp.resources.get(idResource);
    that.$('.plannerDay-'+day+' ol.dd-list').append( that.resourcePlanItemTemplate({ resource : resource.toJSON() }) );
    /*-------------------------------------- AÑADIR ---------------------------*/

  },
  removeResourceToDay: function(e){
    $(e.target).closest('.dd-item').remove();
  },
  resourceInPlan: function( idResource ){
    var that = this;
    var days = [];

    return days;
  },



  fromHtmlToModel: function() {
    var that = this;

    var days = [];

    $('.gzznestable').each(function( index ) {
      var day = [];
      $($(this).nestable('serialize')).each( function( i, planItemId ) {
        day.push({ id:planItemId });
      });
      days.push(day);
    });

    that.parentTp.tpData.set('list', days);

  },

  fromModeltoHtml: function() {
    var that = this;


  }

});
