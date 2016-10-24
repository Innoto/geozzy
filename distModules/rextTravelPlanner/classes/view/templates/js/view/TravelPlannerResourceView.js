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

  },

  initialize: function( parentTp, idResource ) {
    var that = this;

    that.delegateEvents();
    that.parentTp = parentTp;
    that.idResource = idResource;
    that.planDays = 1 + that.parentTp.tpData.get('checkout').diff(that.parentTp.tpData.get('checkin'), 'days');

    that.modalTemplate = _.template( geozzy.travelPlannerComponents.modalMdTemplate );
    that.render();
  },

  render: function() {
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'resourceTpModal', 'modalTitle': __('Add to Plan') }) );
    that.el = '#resourceTpModal'
    that.$el = $(that.el);

    item = that.parentTp.resources.get(that.idResource);

    var checkin = moment(that.parentTp.tpData.get('checkin'));
    var dates = [];
    for (i = 0; i < that.planDays; i++) {
      console.log(that.parentTp.tpData.get('checkin').format('LL'));
      console.log(checkin.format('LL'));
      console.log(checkin.format('dddd'));
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
  }


});
