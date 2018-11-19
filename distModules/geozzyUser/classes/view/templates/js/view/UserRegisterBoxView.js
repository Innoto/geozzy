var geozzy = geozzy || {};
if(!geozzy.userSessionComponents) geozzy.userSessionComponents={};

geozzy.userSessionComponents.userRegisterView = Backbone.View.extend({
  userSessionParent : false,
  userRegisterTemplate : false,
  modalTemplate : false,

  events: {
    "click .close": "abortRegisterModal",
    "click .gzzAppPrivacidad": "showPrivacidad"
  },

  initRegisterModal: function(){
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'registerModal', 'modalTitle': 'Register' }) );
    $("#registerModal .modal-body").html( that.userRegisterTemplate() );
    $("#registerModal .modal-body .registerModalForm").load( '/'+cogumelo.publicConf.C_LANG+'/geozzyuser/register' );
    $("#registerModal").modal({
      'show' : true,
      'keyboard': false,
      'backdrop' : 'static'
    });
    $("#registerModal").on('hidden.bs.modal', function (e) {
      $(e.target).remove();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });
    that.el = "#registerModal";
    that.$el = $(that.el);
    that.delegateEvents();
  },
  closeRegisterModal: function() {
    var that = this;
    $("#registerModal").modal('hide');
  },
  abortRegisterModal: function() {
    var that = this;
    if( that.userSessionParent.abortCallback ){
      that.userSessionParent.abortCallback();
    }
  },
  initialize: function( opts ) {
    var that = this;
    that.userRegisterTemplate = _.template( geozzy.userSessionComponents.userRegisterBoxTemplate );
    that.modalTemplate = _.template( geozzy.userSessionComponents.modalMdTemplate );
    that.initRegisterModal();
  },
  render: function() {
    var that = this;
    //that.$el.html( that.tpl({ content: contentHtml }) );
  },
  showPrivacidad: function(e) {
    var that = this;
    that.userSessionParent.getPrivacidad( function(data) {
      if(data){
        geozzy.generateModal(data.modal);
      }
    });
  }

});
