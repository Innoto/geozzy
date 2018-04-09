var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerPrintDayView = Backbone.View.extend({
  el: "#printDayTpModal",
  getDatesTpModalTemplate : false,
  modalTemplate : false,
  parentTp : false,
  paramDay: false,

  events: {
    //'click .optimizeRoute' : 'optimizeRoute'
  },

  initialize: function( parentTp, day ) {
    var that = this;
    that.delegateEvents();
    that.parentTp = parentTp;
    that.modalTemplate = _.template( geozzy.travelPlannerComponents.modalFullTemplate );
    that.paramDay = day;
    that.render();
  },

  render: function() {
    var that = this;
    $('body').append( that.modalTemplate({ 'modalId': 'printDayTpModal', 'modalTitle': __('Print Day') }) );
    that.el = '#printDayTpModal'
    that.$el = $(that.el);

    that.printContent();

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

    setTimeout(function(){ that.$el.print(); }, 2000);

    return that;
  },

  printContent: function(){
    var that = this;
    var resSelectedInDay = [];
    var resSelectedAllInDay = [];
    $(that.parentTp.tpData.get('list')).each( function(iday,day) {
      $(day).each( function(i,item){
        if( that.paramDay == iday ){
          resSelectedAllInDay.push(item);
          resSelectedInDay.push(item.id);
        }
      });
    });

    var resourcesToList = [];
    resourcesToList = that.parentTp.resources;
    resourcesToList = new geozzy.collection.ResourceCollection( resourcesToList.filterById(resSelectedInDay) );

    var data = {
      day : parseInt(that.paramDay)+1,
      route: that.parentTp.travelPlannerMapPlanView.directionsServiceRequest[that.paramDay]
    }

    if(resourcesToList.length > 0 ){
      data.resources = resourcesToList.toJSON();
      data.resourcesTimes = resSelectedAllInDay;
    }
console.log(data);


/*
$.each(response.routes[0].legs, function( i, leg ) {
  totalTimeTransport += leg.duration.value;

  var stringTime = '';

  if( time !== ''){
    if(cogumelo.publicConf.geozzyTravelPlanner.routeMode && cogumelo.publicConf.geozzyTravelPlanner.routeMode === 'WALKING'){
      stringTime = '+<i class="fa fa-male"></i> '+time;
    }else{
      stringTime = '+ <i class="fa fa-car"></i> '+time;
    }
  }


});
*/

    that.printDayTpModalTemplate = _.template( $('#printDayTpModalTemplate').html() );
    that.$('.modal-body').html( that.printDayTpModalTemplate({data: data}) );
  },

  closeModalResource: function(){
    var that = this;
    that.$el.modal('hide');
  }
});
