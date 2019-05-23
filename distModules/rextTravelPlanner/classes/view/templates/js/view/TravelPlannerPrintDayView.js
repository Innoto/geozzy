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
    that.el = '#printDayTpModal';
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

    setTimeout(function(){
      that.$el.print({timeout: 1000});
     }, 2000);

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
    };

    if(resourcesToList.length > 0 ){
      data.resources = resourcesToList.toJSON();
      data.resourcesTimes = resSelectedAllInDay;
    }


  var totalTimeTransport = 0;
  var totalResourcesTimes = 0;
  var routeTimes = [];
  routeTimes.push({stringTime : ''});

  //Calculamos el tiempo total de los recursos del usuario y lo pasamos a horas y minutos:
  $.each(data.resourcesTimes, function(i, e) {
     totalResourcesTimes += parseInt(e.time);
  });

  var minutes = totalResourcesTimes % 60;
  var hours = (totalResourcesTimes - minutes) / 60;
  var stringTotalResourceTimes = hours + "h " + minutes + " min";

  $.each(data.route.routes[0].legs, function( i, leg ) {
     totalTimeTransport += parseInt(leg.duration.value);

    var stringTime = '';


    if( leg.duration.value !== ''){
      if(cogumelo.publicConf.geozzyTravelPlanner.routeMode && cogumelo.publicConf.geozzyTravelPlanner.routeMode === 'WALKING'){
        stringTime = '<i class="fa fa-male"></i> '+leg.duration.text;
        stringRouteMode = '<i class="fa fa-male"></i>';
      }else{
        stringTime = '<i class="fa fa-car"></i> '+leg.duration.text;
        stringRouteMode = '<i class="fa fa-car"></i>';
      }
    }

    routeTimes.push({stringTime});

  });


  //Convertimos los minutos totales de transporte en horas y minutos para pintarlos en el tpl:
  var seconds = totalTimeTransport % 60;
  var rest = (totalTimeTransport - seconds) / 60;
  minutes = rest % 60;
  hours = (rest - minutes) / 60;
  var stringTotalTimeTransport = hours + "h " + minutes + " min";

  data.routeTimes = routeTimes;
  data.stringTotalTimeTransport = stringTotalTimeTransport;
  data.stringTotalResourceTimes = stringTotalResourceTimes;
  data.stringRouteMode = stringRouteMode;

  cogumelo.log(data);


    that.printDayTpModalTemplate = _.template( $('#printDayTpModalTemplate').html() );
    that.$('.modal-body').html( that.printDayTpModalTemplate({data: data}) );
  },

  closeModalResource: function(){
    var that = this;
    that.$el.modal('hide');
  }
});
