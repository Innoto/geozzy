var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerHelpView = Backbone.View.extend({
  el: "#helpTpModal",
  helpTpModalTemplate : false,
  modalTemplate : false,
  parentTp : false,
  onCloseGetDates: false,

  events: {
    "click .selectYourDates": "closeModalResource"
  },

  initialize: function( parentTp, onCloseGetDates ) {
    var that = this;
    that.delegateEvents();
    that.parentTp = parentTp;
    that.modalTemplate = _.template( geozzy.travelPlannerComponents.modalMdTemplate );
    that.onCloseGetDates = onCloseGetDates;
    that.render();
  },

  render: function() {
    var that = this;
    $('body').append( that.modalTemplate({ 'modalId': 'helpTpModal' }) );
    that.el = '#helpTpModal';
    that.$el = $(that.el);

    that.helpTpModalTemplate = _.template( $('#helpTpModalTemplate').html() );
    that.$('.modal-body').html( that.helpTpModalTemplate() );

    that.$el.modal({
      'show' : true,
      'keyboard': false,
      'backdrop' : 'static'
    });
    that.$el.on('hidden.bs.modal', function (e) {
      e.target.remove();
      if(that.onCloseGetDates){
        that.parentTp.getDates();
      }
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

    return that;
  },

  closeModalResource: function(){
    var that = this;
    that.$el.modal('hide');
  }
});
