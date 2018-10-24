var geozzy = geozzy || {};
geozzy.app = geozzy.app || {};

$(document).ready(function(){
  $(document).on('click','.navbar-collapse.in',function(e) {
    $(this).collapse('hide');
  });
  gzzAppPrivacidad();
});

geozzy.app.getPrivacidad = function getPrivacidad( privacidadSuccess ){
  $.ajax({
    url: "/"+cogumelo.publicConf.C_LANG+"/api/core/resourcelist/filters/idName/filtervalues/privacidad/",
    type: 'GET',
    success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
      var data = {
        resData : $jsonData,
        modal : {
          title: '<img class="iconModal img-fluid" src="'+cogumelo.publicConf.media+'/img/iconModal.png">',
          classCss: 'gzzAppPrivacidadModal',
          htmlBody: '<h1>'+$jsonData[0].title+'</h1><div class="content">'+$jsonData[0].content+'</div>',
          size: 'lg',
          btnClass: 'btnProfesores'
        }
      };
      privacidadSuccess(data);
    }
  });
};


function gzzAppPrivacidad() {
  if( geozzy.userSessionInstance ) {
    geozzy.userSessionInstance.getPrivacidad = function(success){
      geozzy.app.getPrivacidad(success);
    };
  }

  $('.gzzAppPrivacidad').on('click', function(e){
    geozzy.app.getPrivacidad( function(data){
      if(data){
        geozzy.generateModal(data.modal);
      }
    });
  });
}


$.fn.gzzCounter = function( options ){
  var that = this;
  var field = $(this);
  var defaults = {
    maxLength: 2000
  };
  var settings = $.extend( {}, defaults, options );
  field.parent().append('<div class="gzzCounterContainer"><span class="'+field.attr('name')+'_gzzCounter"></span>'+ __(" caracteres")+'</div>');
  var infoLabel = field.parent().find('.'+field.attr('name')+'_gzzCounter');

  field.keyup(function(){ that.countAndPrint(); });
  field.bind('mouseover', function(event){ setTimeout(that.countAndPrint(), 10); });
  field.bind('paste', function(event){ setTimeout(that.countAndPrint(), 10); });

  that.countAndPrint = function () {
    var length = $(this).val().length;
    length = settings.maxLength-length;
    infoLabel.text(length);
  };
  that.countAndPrint();
};


function fixVhChromeMobile() {
  var userAgent = navigator.userAgent.toLowerCase();
  var isAndroidChrome = /chrome/.test(userAgent) && /android/.test(userAgent);
  var isIOSChrome = /crios/.test(userAgent);

  if (isAndroidChrome || isIOSChrome) {
    $('.fixVhChromeMobile').innerHeight( $(this).innerHeight() );
  }
}
