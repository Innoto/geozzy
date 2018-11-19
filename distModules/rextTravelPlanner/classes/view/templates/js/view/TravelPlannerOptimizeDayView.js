var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerOptimizeDayView = Backbone.View.extend({
  el: "#optimizeDayTpModal",
  getDatesTpModalTemplate : false,
  modalTemplate : false,
  parentTp : false,
  paramDay: false,

  events: {
    'click .optimizeRoute' : 'optimizeRoute'
  },

  initialize: function( parentTp, day ) {
    var that = this;
    that.delegateEvents();
    that.parentTp = parentTp;
    that.modalTemplate = _.template( geozzy.travelPlannerComponents.modalMdTemplate );
    that.paramDay = day;
    that.render();
  },

  render: function() {
    var that = this;
    $('body').append( that.modalTemplate({ 'modalId': 'optimizeDayTpModal', 'modalTitle': __('Select Dates') }) );
    that.el = '#optimizeDayTpModal';
    that.$el = $(that.el);
    that.infoOptimizeContent();
    that.$el.modal({
      'show' : true,
      'keyboard': false,
      'backdrop' : 'static'
    });
    that.$el.on('hidden.bs.modal', function (e) {
      e.target.remove();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

    return that;
  },

  infoOptimizeContent: function(){
    var that = this;
    var resSelectedInDay = [];
    $(that.parentTp.tpData.get('list')).each( function(iday,day) {
      $(day).each( function(i,item){
        if( that.paramDay == iday && (i == 0 || i+1 == day.length) ){
          resSelectedInDay.push(item.id);
        }
      });
    });

    var resourcesToList = [];
    resourcesToList = that.parentTp.resources;
    resourcesToList = new geozzy.collection.ResourceCollection( resourcesToList.filterById(resSelectedInDay) );

    if(resourcesToList.length === 1 ){
      firstLoc = resourcesToList.get(resSelectedInDay[0]);
      lastLoc = resourcesToList.get(resSelectedInDay[0]);
    }
    else{
      $.each(resSelectedInDay, function( i, res ){
        if(i === 0){
          firstLoc = resourcesToList.get(res);
        }
        else{
          lastLoc = resourcesToList.get(res);
        }
      });
    }

    var data = {
      init : firstLoc.toJSON(),
      end : lastLoc.toJSON(),
      day : parseInt(that.paramDay)+1
    };

    that.optimizeDayTpModalTemplate = _.template( $('#optimizeDayTpModalTemplate').html() );
    that.$('.modal-body').html( that.optimizeDayTpModalTemplate({data: data}) );
  },

  optimizeRoute: function(){
    var that = this;
    that.parentTp.showMapOptimize(that.paramDay);
    that.closeModalResource();
  },

  closeModalResource: function(){
    var that = this;
    that.$el.modal('hide');
  }
});
