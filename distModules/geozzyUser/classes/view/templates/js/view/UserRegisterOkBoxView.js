var geozzy = geozzy || {};
if(!geozzy.userSessionComponents) geozzy.userSessionComponents={};

geozzy.userSessionComponents.userRegisterOkView = Backbone.View.extend({
  userSessionParent : false,
  userRegisterOkTemplate : false,
  modalTemplate : false,

  events: {

  },

  initRegisterOkModal: function(){
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'registerOkModal', 'modalTitle': 'Register' }) );
    $("#registerOkModal .modal-body").html( that.userRegisterOkTemplate() );
    $("#registerOkModal").modal({
      'show' : true
    });
    $("#registerOkModal").on('hidden.bs.modal', function (e) {
      $(e.target).remove();
      that.userSessionParent.finishCallback();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

  },
  closeRegisterModal: function() {
    var that = this;
    $("#registerModal").modal('hide');
  },
  initialize: function( opts ) {
    var that = this;
    that.userRegisterOkTemplate = _.template( geozzy.userSessionComponents.userRegisterOkBoxTemplate );
    that.modalTemplate = _.template( geozzy.userSessionComponents.modalMdTemplate );
    that.initRegisterOkModal();
  },
  render: function() {
    var that = this;
    //that.$el.html( that.tpl({ content: contentHtml }) );
  }

});
