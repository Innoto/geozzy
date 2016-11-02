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
        dayName: checkin.format('dddd'),
        day: checkin.format('DD'),
        month: checkin.format('MMM'),
        inPlan: false
      };

      if($.inArray(i, selectedDays) !== -1){
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

    that.$('.resourceTp form').validate({
      lang: cogumelo.publicConf.C_LANG,
      rules: {
        'hlong-hour':{ 'min': 0 , 'max':23, 'digits': true, required: true },
        'hlong-minutes':{ 'min':0, 'max':59, 'digits': true, required: true }
      },
      messages: {
        'hlong-hour': __('Introduce entre 0 y 23 horas'),
        'hlong-minutes': __('Introduce entre 0 y 59 minutos')
      }
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
    if( that.$('.resourceTp form').valid()){
      var daysSelectorActive = $('.selectorDays li.active');
      var daysActive = [];
      daysSelectorActive.each(function( index ) {
        daysActive.push($( this ).attr('data-day'));
      });

      var minVisitTime = 0;
      minVisitTime = (parseInt(that.$('.resourceTp .hlong-hour').val()) * 60) + parseInt(that.$('.resourceTp .hlong-minutes').val());

      that.parentTp.travelPlannerPlanView.addResourcesPlan(that.idResource, daysActive, minVisitTime);
      that.closeModalResource();
    }
  }
});
