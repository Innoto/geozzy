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
        date: checkin.format('L'),
        dayName: checkin.format('dddd'),
        day: checkin.format('DD'),
        month: checkin.format('MMM')
      };
      checkin.add(1, 'days');
      that.$('.travelPlannerPlanDaysContainer').append( that.dayTemplate({ day: day }) );
    }

    $('.gzznestable.dd').nestable({
      'maxDepth': 1,
      'dragClass': "gzznestable dd-dragel",
      callback: function(l, e) {
        that.fromHtmlToModel();
        that.updateTotalTimes();
      }
    });

    that.fromModeltoHtml();
    that.updateTotalTimes();
  },
  addResourcesPlan: function (idResource, days, t){
    var that = this;
    $.each( days, function(i,d){
      that.addResourceToDay( idResource, d, t);
    });
    that.fromHtmlToModel();
    that.updateTotalTimes();
  },
  addResourceToDay: function( idResource, day, t){
    var that = this;
    var resource = that.parentTp.resources.get(idResource);



    //if( resouce !)
    if(typeof resource != 'undefined') {

      var resourceJSON = resource.toJSON();

      resourceJSON.timeFormated = that.getFormatedTime(t);
      //resourceJSON.timeFormated = t;

      resourceJSON.serializedData = that.serializeRow({
        id: idResource,
        time: t
      });

      if(that.$('.plannerDay-'+day+' .dd ol.dd-list').length === 0){
        that.$('.plannerDay-'+day+' .dd').html('<ol class="dd-list"></ol>');
      }
      that.$('.plannerDay-'+day+' ol.dd-list').append( that.resourcePlanItemTemplate({ resource : resourceJSON }) );
    }
    /*-------------------------------------- AÑADIR ---------------------------*/

  },
  removeResourceToDay: function(e){
    var that = this;
    var list = $(e.target).closest('.dd-list');
    $(e.target).closest('.dd-item').remove();
    if(list.children().length === 0){
      list.parent().html('<div class="dd-empty"></div>');
    }

    that.fromHtmlToModel();
  },
  resourceInPlan: function( idResource ){
    var that = this;
    var days = [];
    var list = that.parentTp.tpData.get('list');

    $.each( list, function( k, day ) {

      var inDay = $.grep(day, function(e){
        return e.id == idResource;
      })[0];
      if(inDay){
        days.push(k);
      }
    });

    return days;
  },

  getFormatedTime: function(mins) {
    var h = mins / 60 | 0,
        m = mins % 60 | 0;


    return h + ' hours ' + m + ' min';
  },

  fromHtmlToModel: function() {
    var that = this;

    var days = [];

    $('.gzznestable').each(function( index ) {
      var day = [];
      $($(this).nestable('serialize')).each( function( i, planItemId ) {
        //console.log(planItemId);
        day.push(planItemId.id);
      });
      days.push(day);
    });

    that.parentTp.tpData.set('list', days);
    //console.log(that.parentTp.tpData.toJSON())
    that.parentTp.tpData.saveData();
  },

  fromModeltoHtml: function() {
    var that = this;

    $(that.parentTp.tpData.get('list')).each( function(iday,day) {
      $(day).each( function(i,item){
        that.addResourceToDay( item.id, iday, item.time );
      });
    });

  },

  updateTotalTimes: function() {
    var that = this;

    $(that.parentTp.tpData.get('list')).each( function(iday,day) {
      var hoursInDay = 0;
      $(day).each( function(i,item){
        hoursInDay += parseInt(item.time);
      });
      $('.plannerDay-' + iday +' .infoTime span').html( that.getFormatedTime(hoursInDay));
    });

  },

  serializeRow: function( rowObj ) {
    var that = this;
    return JSON.stringify( rowObj );
  }




});
