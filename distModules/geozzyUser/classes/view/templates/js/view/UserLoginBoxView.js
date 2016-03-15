var geozzy = geozzy || {};
if(!geozzy.userSessionComponents) geozzy.userSessionComponents={};

geozzy.userSessionComponents.userLoginView = Backbone.View.extend({

  userLoginTemplate : _.template( geozzy.userSessionComponents.userLoginBoxTemplate ),
  modalTemplate : _.template( geozzy.userSessionComponents.modalMdTemplate ),

  events: {

  },

  initLoginModal: function(){
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'loginModal', 'modalTitle': 'Login' }) );
    $("#loginModal .modal-body").html( that.userLoginTemplate() );
    $("#loginModal .modal-body .loginForm").load( '/geozzyuser/login' );
    $("#loginModal").modal({
      'show' : true,
      'backdrop' : 'static'
    });
    $("#loginModal").on('hidden.bs.modal', function (e) {
      e.target.remove();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

  },
  closeLoginModal: function() {
    var that = this;
    $("#loginModal").modal('hide');
  },
  initialize: function( opts ) {
    var that = this;
    that.initLoginModal();
  },
  render: function() {
    var that = this;
    //that.$el.html( that.tpl({ content: contentHtml }) )
  }

});
