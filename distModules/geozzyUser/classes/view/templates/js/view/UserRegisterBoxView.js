var geozzy = geozzy || {};
if(!geozzy.userSessionComponents) geozzy.userSessionComponents={};

geozzy.userSessionComponents.userRegisterView = Backbone.View.extend({
  userSessionParent : false,
  userRegisterTemplate : false,
  modalTemplate : false,

  events: {

  },

  initRegisterModal: function(){
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'registerModal', 'modalTitle': 'Login' }) );
    $("#registerModal .modal-body").html( that.userRegisterTemplate() );
    $("#registerModal .modal-body .registerModalForm").load( '/geozzyuser/login' );
    $("#registerModal").modal({
      'show' : true,
      'backdrop' : 'static'
    });
    $("#registerModal").on('hidden.bs.modal', function (e) {
      e.target.remove();
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
    that.userRegisterTemplate = _.template( geozzy.userSessionComponents.userRegisterBoxTemplate ),
    that.modalTemplate = _.template( geozzy.userSessionComponents.modalMdTemplate ),
    that.initRegisterModal();
  },
  render: function() {
    var that = this;
    //that.$el.html( that.tpl({ content: contentHtml }) )
  }

});
