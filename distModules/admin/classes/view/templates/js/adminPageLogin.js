
$( document ).ready(function(){

  $('.adminLogin .initRecoveryPass').on('click', function(){
    initRecoveryPass();
  });
  $('.adminLogin .recoveryPassSubmit').on('click', function(){
    sendRecoveryPass();
  });

});


function initRecoveryPass(){
  $('.adminLogin .initRecoveryPass').hide();
  $('.adminLogin .recoveryPasswordForm').show();
}

function sendRecoveryPass(){
  var that = this;
  var userEmail = $('.adminLogin .recoveryPassEmail').val();
  var captchaValue = grecaptcha.getResponse();

  $.ajax({
    url: "/api/core/userunknownpass",
    data: {'user': userEmail, 'captcha': captchaValue },
    method: "POST",
  }).done(function( data ) {
    cogumelo.log(data);
    if(data){
      that.$('.loginInfoContainer').hide();
      $('.adminLogin .recoveryPasswordForm').hide();
      $('.adminLogin .recoveryCaptchaError').hide();
      $('.adminLogin .recoveryPasswordFinalMsg').show();
    }else{
      $('.adminLogin .recoveryCaptchaError').show();
    }
  });


}
