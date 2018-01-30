var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerGetDatesView = Backbone.View.extend({
  el: "#getDatesTpModal",
  getDatesTpModalTemplate : false,
  modalTemplate : false,
  parentTp : false,

  events: {
    'click .selectorDays li': 'selectorDays',
    'click .cancelAdd' : 'closeModalResource',
    'click .acceptAdd' : 'addToPlan',
    'click .cancelEdit' : 'closeModalResource',
    'click .acceptEdit' : 'editToPlan'
  },

  initialize: function( parentTp ) {
    var that = this;
    that.delegateEvents();
    that.parentTp = parentTp;
    that.modalTemplate = _.template( geozzy.travelPlannerComponents.modalMdTemplate );
    that.render();
  },

  render: function() {
    var that = this;
    $('body').append( that.modalTemplate({ 'modalId': 'getDatesTpModal', 'modalTitle': __('Select Dates') }) );
    that.el = '#getDatesTpModal'
    that.$el = $(that.el);

    that.getDatesTpModalTemplate = _.template( $('#getDatesTpModalTemplate').html() );
    that.$('.modal-body').html( that.getDatesTpModalTemplate() );
    that.initDates();

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

  initDates: function(){
    var that = this;

    var calDateFormat = that.parentTp.dateFormat;
    var calCheckIn = false;
    var calCheckOut = false;

    if( that.parentTp.tpData.get('checkin') === null || that.parentTp.tpData.get('checkout') === null ){
      calCheckIn = moment().add( 0, 'days' );
      calCheckOut = moment().add( 0, 'days' );
    }else{
      calCheckIn = that.parentTp.momentDate( that.parentTp.tpData.get('checkin') );
      calCheckOut = that.parentTp.momentDate( that.parentTp.tpData.get('checkout') );
    }

    var singleDatePicker = false;
    if(cogumelo.publicConf.mod_detectMobile_isMobile){
      singleDatePicker = true;
    }

    $( '#getDatesTpInput' ).daterangepicker(
      {
        'parentEl': '#getDatesTpModal',
        'showCustomRangeLabel': true,
        'startDate':  calCheckIn,
        'minDate':  calCheckIn,
        'endDate':  calCheckOut,
        'autoApply': true,
        'locale': {
          'format': calDateFormat,
          'firstDay': 1
        }
      },
      function( start, end, label ) {
        that.parentTp.tpData.set('checkin', start.format( that.parentTp.timeServerFormat ) );
        that.parentTp.tpData.set('checkout', end.format( that.parentTp.timeServerFormat ) );

        that.parentTp.initPlan();
        that.parentTp.travelPlannerPlanView.fromHtmlToModel();
        if(!cogumelo.publicConf.mod_detectMobile_isMobile){
          $('#checkTpDates').data('daterangepicker').setStartDate( start );
          $('#checkTpDates').data('daterangepicker').setEndDate( end );
        }
      }
    );
  },


  closeModalResource: function(){
    var that = this;
    that.$el.modal('hide');
  }
});
