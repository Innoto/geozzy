var geozzy = geozzy || {};
if(!geozzy.userSessionComponents) geozzy.userSessionComponents={};

geozzy.userSessionComponents.userLoginView = Backbone.View.extend({

  userSessionParent : false,
  userLoginTemplate : false,
  modalTemplate : false,

  events: {
    "click .gotoregister": "goToRegister",
    "click .close": "abortLoginModal",
    "click .initRecoveryPass": "initRecoveryPass",
    "click .recoveryPassSubmit": "sendRecoveryPass"
  },

  initLoginModal: function(){
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'loginModal', 'modalTitle': 'Login' }) );
    $("#loginModal .modal-body").html( that.userLoginTemplate() );
    $("#loginModal .modal-body .loginModalForm").load( '/'+cogumelo.publicConf.C_LANG+'/geozzyuser/login' );
    $("#loginModal").modal({
      'show' : true,
      'keyboard': false,
      'backdrop' : 'static'
    });
    $("#loginModal").on('hidden.bs.modal', function (e) {
      $(e.target).remove();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

    that.el = "#loginModal";
    that.$el = $(that.el);
    that.delegateEvents();
  },
  closeLoginModal: function() {
    var that = this;
    $("#loginModal").modal('hide');
  },
  abortLoginModal: function() {
    var that = this;
    if( that.userSessionParent.abortCallback ){
      that.userSessionParent.abortCallback();
    }
  },
  initialize: function( opts ) {
    var that = this;

    that.userLoginTemplate = _.template( geozzy.userSessionComponents.userLoginBoxTemplate );
    that.modalTemplate = _.template( geozzy.userSessionComponents.modalMdTemplate );

    that.initLoginModal();
  },
  render: function() {
    var that = this;
    //that.$el.html( that.tpl({ content: contentHtml }) );
  },
  goToRegister: function() {
    var that = this;
    that.closeLoginModal();
    that.userSessionParent.initRegisterBox();
  },
  initRecoveryPass: function(){
    var that = this;
    $('#loginModal .initRecoveryPass').hide();
    $('#loginModal .recoveryPasswordForm').show();
  },
  sendRecoveryPass:function(){
    var that = this;
    var userEmail = $('.recoveryPassEmail').val();
    var captchaValue = grecaptcha.getResponse();

    $.ajax({
      url: "/api/core/userunknownpass",
      data: {'user': userEmail, 'captcha': captchaValue },
      method: "POST",
    }).done(function( data ) {
      cogumelo.log(data);
      if(data){
        that.$('.loginInfoContainer').hide();
        $('#loginModal .recoveryPasswordForm').hide();
        $('#loginModal .recoveryCaptchaError').hide();
        $('#loginModal .recoveryPasswordFinalMsg').show();
      }else{
        $('#loginModal .recoveryCaptchaError').show();
      }
    });

  }

});
