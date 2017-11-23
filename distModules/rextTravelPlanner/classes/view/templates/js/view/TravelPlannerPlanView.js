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
    'click .travelPlannerPlan .plannerDay .showMap': 'showMapDay',
    'click .travelPlannerPlan .plannerDay .dd-item .btnDelete': 'removeResourceToDay',
    'click .travelPlannerPlan .plannerDay .dd-item .btnEdit': 'bindEditResourceToDay'
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
        that.parentTp.travelPlannerMapPlanView.render();
      }
    });

    that.fromModeltoHtml();
    that.updateTotalTimes();
    that.parentTp.travelPlannerMapPlanView.render();
  },
  showMapDay: function(e){
    var that = this;
    var day = $(e.target).closest('.plannerDay').attr('data-day');
    that.parentTp.showMap( day );
  },
  addResourcesPlan: function (idResource, days, t){
    var that = this;
    $.each( days, function(i,d){
      that.addResourceToDay( idResource, d, t);
    });

    that.fromHtmlToModel();
    that.updateTotalTimes();
    that.parentTp.travelPlannerMapPlanView.render();
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

  },
  bindEditResourceToDay: function(e){
    var that = this;

    var day = $(e.target).closest('.plannerDay');
    var item = $(e.target).closest('.dd-item');

    var resourceInfo = $.parseJSON(item.attr('data-id'));
    resourceInfo.day = day.attr('data-day');
    resourceInfo.positionDay = day.find('.dd-list li').index(item);

    that.parentTp.editResourceToPlan( resourceInfo );
  },
  editResourcesPlan: function( data ){
    var that = this;
    var resource = that.parentTp.resources.get(data.id);

    //if( resouce !)
    if(typeof resource != 'undefined') {

      var resourceJSON = resource.toJSON();
      resourceJSON.timeFormated = that.getFormatedTime(data.time);
      resourceJSON.serializedData = that.serializeRow({
        id: data.id,
        time: data.time
      });
      $(that.$('.plannerDay-'+data.day+' ol.dd-list li')[data.positionDay]).replaceWith( that.resourcePlanItemTemplate({ resource : resourceJSON }) );
    }

    that.fromHtmlToModel();
    that.updateTotalTimes();
  },
  removeResourceToDay: function(e){
    var that = this;
    var list = $(e.target).closest('.dd-list');
    $(e.target).closest('.dd-item').remove();
    if(list.children().length === 0){
      list.parent().html('<div class="dd-empty"></div>');
    }

    that.fromHtmlToModel();
    that.updateTotalTimes();
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
        day.push(planItemId.id);
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
