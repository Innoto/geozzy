
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

  if(userEmail !== ''){
    $.ajax({
      url: "/api/core/userunknownpass",
      data: {'user': userEmail },
      method: "POST",
    }).done(function( data ) {
      if(data){
        $('.adminLogin .recoveryPasswordForm').hide();
        $('.adminLogin .recoveryPasswordFinalMsg').show();
      }
    });
  }
}
