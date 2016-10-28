var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerResourceView = Backbone.View.extend({
  el: "#resourceTpModal",
  resourceTemplate : false,
  modalTemplate : false,
  parentTp : false,
  idResource : false,
  planDays : false,

  events: {
    'click .selectorDays li': 'selectorDays',
    'click .cancelAdd' : 'closeModalResource',
    'click .acceptAdd' : 'addToPlan'
  },

  initialize: function( parentTp, idResource ) {
    var that = this;

    that.delegateEvents();
    that.parentTp = parentTp;
    that.idResource = idResource;

    var checkin =  that.parentTp.momentDate( that.parentTp.tpData.get('checkin') );
    var checkout = that.parentTp.momentDate( that.parentTp.tpData.get('checkout') );

    that.planDays = 1 + checkout.diff( checkin, 'days');

    that.modalTemplate = _.template( geozzy.travelPlannerComponents.modalMdTemplate );
    that.render();
  },

  render: function() {
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'resourceTpModal', 'modalTitle': __('Add to Plan') }) );
    that.el = '#resourceTpModal'
    that.$el = $(that.el);

    item = that.parentTp.resources.get(that.idResource);

    var checkin = that.parentTp.momentDate(that.parentTp.tpData.get('checkin'));
    var dates = [];

    var selectedDays = that.parentTp.travelPlannerPlanView.resourceInPlan(that.idResource);
    for (i = 0; i < that.planDays; i++) {
      dates[i] = {
        id: i,
        date: checkin.format('LL'),
        dayName: checkin.format('ddd'),
        day: checkin.format('DD'),
        month: checkin.format('MMM'),
        inPlan: false
      };
      if($.inArray(i, selectedDays)){
        dates[i].inPlan = true;
      }
      checkin.add(1, 'days');
    }

    that.resourceTemplate = _.template( $('#resourceTpModalTemplate').html() );
    that.$('.modal-body').html( that.resourceTemplate({ resource: item.toJSON(), dates: dates }) );

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

  closeModalResource: function(){
    var that = this;
    that.$el.modal('hide');
  },

  selectorDays: function (e){
    var day = $(e.target).closest('.selectorDays li');
    if(day.hasClass('active')){
      day.removeClass('active');
    }else{
      day.addClass('active');
    }
  },

  addToPlan: function(e){
    var that = this;
    var daysSelectorActive = $('.selectorDays li.active');
    var daysActive = [];
    daysSelectorActive.each(function( index ) {
      daysActive.push($( this ).attr('data-day'));
    });
    that.parentTp.travelPlannerPlanView.addResourcesPlan(that.idResource, daysActive, that.parentTp.travelPlannerDefaultVisitTime);
    that.closeModalResource();
  }
});
