cogumelo.log('QR Code Generator (qrcodeGenerator.js)');

$( document ).ready( function() {
  qrCode.boxBtnGenerator();
} );

var qrCode = {

  langActive: cogumelo.publicConf.langDefault,
  qrImgLang: cogumelo.publicConf.lang_available,

  boxBtnGenerator: function() {
    var that = this;

    $( 'body' ).popover( {
      selector: '.btn-qrgenerator[data-toggle="popover"]',
      template: '<div class="popover popover-qrgenerator popoverjs-mark" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
      html: true,
      placement: 'auto'
    } );

    var html_qrcode = ''+
    '<div class="box-btn-qrgenerator">'+
      '<a class="btn-qrgenerator popoverjs-mark" data-toggle="popover" title="'+ __('QR Code Generator') +'"><i class="fas fa-qrcode fa-lg"></i></a>'+
    '</div>';

    $( '.langSwitch-wrap[class*="langSwitch-urlAlias_"]' ).prepend( html_qrcode );

    that.loadQrUrlAlias();
    that.bindSwitchLang();
    that.eventsLoadClose();
  },

  loadQrUrlAlias: function() {
    var that = this;

    $.each( cogumelo.publicConf.langAvailableIds, function( i, langAvailable ) {
      var inputValueUrl = $( 'input.cgmMForm-field.cgmMForm-field-urlAlias_' + langAvailable ).val();
      var urlResFull = cogumelo.publicConf.site_host + '/' + langAvailable + inputValueUrl;
      that.qrImgLang[langAvailable].qrcode = that.qrGenerator( urlResFull );
    } );
  },

  qrGenerator: function( urlAlias ) {
    var that = this;

    var cellSize = 10;
    var margin = cellSize * 4;

    var typeNumber = 7;
    var errorCorrectionLevel = 'M';

    var qr = qrcode( typeNumber, errorCorrectionLevel );
    qr.addData( urlAlias );
    qr.make();
    var qrCreate = {
      img: qr.createImgTag( cellSize, margin ),
      url: qr.createDataURL( cellSize, margin )
    };

    return qrCreate;
  },

  loadContentBoxQR: function() {
    var that = this;

    cogumelo.log( 'loadContentBoxQR (lang): ',that.langActive );
    var qrTemp = that.qrImgLang[that.langActive];
    var downloadQR = '<a class="linkDownload" href="'+ qrTemp.qrcode.url +'" title="'+ __('QRCode') +'_'+ qrTemp.i18n +'" target="_blank" rel="noopener noreferrer" download>'+ __('QR Code - Donwload') +' (<span class="lang">'+ qrTemp.name +'</span>)</a>';
    var html_box_qr_image = '<div class="box-qr-image" style="text-align:center;">'+ downloadQR +'<div class="qrcode">'+ qrTemp.qrcode.img +'</div></div>';

    return html_box_qr_image;
  },

  bindSwitchLang: function() {
    var that = this;

    $( '.langSwitch-wrap ul.langSwitch li' ).on( 'click', function() {
      var newLang = $( this ).attr( 'data-lang' );
      if( newLang != that.langActive ) {
        that.langActive = newLang;
      }
    } );
  },

  eventsLoadClose: function() {
    var that = this;

    $( '.btn-qrgenerator' ).on( 'inserted.bs.popover', function() {
      $( '.popover-qrgenerator .popover-body' ).html( that.loadContentBoxQR() );
    } );

    $( 'html' ).on( 'mouseup', function( e ) {
      var htmlTarget = $( e.target );
      if( !htmlTarget.closest('.popoverjs-mark').length && $( '.popover-qrgenerator' ).length ) {
        $( '.popover-qrgenerator' ).popover("hide");
      }
    } );
  }
};
