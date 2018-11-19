var geozzy = geozzy || {};

geozzy.generateModal = function( options ) {
  var that = this;
  //classCss, title, htmlBody, successCallback, size, btnClass
  var opts = new Object({
    classCss : 'generateModal',
    title: '',
    htmlBody: 'Hello world',
    size: 'md',
    btnClass: 'btn-primary',
    footerHidden: false,
    show: true,
    autoRemove : true,
    successOpened: function(){ return false; },
    successCallback: function() { return false; }
  });
  that.options = $.extend(true, {}, opts, options );
  that.options.modalClasses = that.options.classCss.replace(' ','.');

  // First Execution
  //
  that.createModalTemplate = function(){
    var html = '';
    html += '<div class="gzzGenerateModal '+that.options.classCss+' modal fade" tabindex="-1" role="dialog">';
      html += '<div class="modal-dialog modal-'+that.options.size+'">';
        html += '<div class="modal-content">';
          html += '<div class="modal-header">';
            html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            if( typeof that.options.title != 'undefined' ){
              html+= '<h3 class="modal-title">'+that.options.title+'</h3>';
            }
          html += '</div>';
          html += '<div class="modal-body">'+that.options.htmlBody+'</div>';
          if( !that.options.footerHidden ) {
            html += '<div class="modal-footer"><button type="button" class="btn '+that.options.btnClass+'" data-dismiss="modal" aria-label="Close">'+__("Accept")+'</button></div>';
          }
        html += '</div>';
      html += '</div>';
    html += '</div>';
    return html;
  };


  that.initModal = function( ) {
    $('body').append(that.createModalTemplate());
    $('.gzzGenerateModal.'+that.options.modalClasses).modal({
      'show' : that.options.show,
      'keyboard': false,
    });
    $('.gzzGenerateModal.'+that.options.modalClasses).on('shown.bs.modal', function (e) {
      if( typeof that.options.successOpened != 'undefined' ){
        that.options.successOpened();
      }
    });
    $('.gzzGenerateModal.'+that.options.modalClasses).on('hidden.bs.modal', function (e) {
      if(that.options.autoRemove){
        $(this).remove();
      }
      if( typeof that.options.successCallback != 'undefined' ){
        that.options.successCallback();
      }
    });
  };

  that.closeModal = function( ) {
    $('.gzzGenerateModal.'+that.options.modalClasses).modal('hide');
  };


  that.initModal();
};
