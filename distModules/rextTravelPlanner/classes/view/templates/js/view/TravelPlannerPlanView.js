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

    var checkin =  that.parentTp.momentDate( that.parentTp.tpData.get('checkin') );
    var checkout = that.parentTp.momentDate( that.parentTp.tpData.get('checkout') );



    that.planDays = 1 + checkout.diff( checkin, 'days');

    that.render();
  },

  render: function() {
    var that = this;
    console.log('Difference is ', that.planDays , 'days');

    that.$('.travelPlannerPlanDaysContainer').html('');

    var checkin = that.parentTp.momentDate( that.parentTp.tpData.get('checkin') );

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
/*
$('.gzznestable').each(function( index ) {
  console.log('DAY'+ (index))
  console.log($(this).nestable('serialize'));
});
*/
        that.fromHtmlToModel();
      }
    });

    that.fromModeltoHtml()
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

    //if( resouce !)
    if(typeof resource != 'undefined') {
      if(that.$('.plannerDay-'+day+' .dd ol.dd-list').length === 0){
        that.$('.plannerDay-'+day+' .dd').html('<ol class="dd-list"></ol>');
      }
      that.$('.plannerDay-'+day+' ol.dd-list').append( that.resourcePlanItemTemplate({ resource : resource.toJSON() }) );
    }
    /*-------------------------------------- AÑADIR ---------------------------*/

  },
  removeResourceToDay: function(e){
    var list = $(e.target).closest('.dd-list');
    $(e.target).closest('.dd-item').remove();
    if(list.children().length === 0){
      list.parent().html('<div class="dd-empty"></div>');
    }
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
        day.push(planItemId);
      });
      days.push(day);
    });
    that.parentTp.tpData.set('list', days);
    that.parentTp.tpData.saveData();
  },

  fromModeltoHtml: function() {
    var that = this;


    $(that.parentTp.tpData.get('list')).each( function(iday,day) {
      $(day).each( function(i,item){
        that.addResourceToDay( item.id, iday );
      });
    });


  }

});
