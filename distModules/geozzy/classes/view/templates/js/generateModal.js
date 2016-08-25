var geozzy = geozzy || {};


geozzy.generateModal = function( classCss, title, htmlBody, successCallback ) {
  var that = this;

  that.classCss = classCss;
  that.title = title;
  that.htmlBody = htmlBody;
  that.successCallback = successCallback;

  // First Execution
  //
  that.createModalTemplate = function(){
    var html = '';
    html += '<div class="gzzGenerateModal '+that.classCss+' modal fade" tabindex="-1" role="dialog">';
      html += '<div class="modal-dialog modal-md">';
        html += '<div class="modal-content">';
          html += '<div class="modal-header">';
            html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            if( typeof that.title != 'undefined' ){
              html+= '<h3 class="modal-title">'+that.title+'</h3>';
            }
          html += '</div>';
          html += '<div class="modal-body">'+that.htmlBody+'</div>';
          html += '<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">'+__("Accept")+'</button></div>';
        html += '</div>';
      html += '</div>';
    html += '</div>';
    return html;
  }


  that.initModal = function( ) {
    $('body').append(that.createModalTemplate());
    $('.gzzGenerateModal').modal({
      'show' : true,
      'keyboard': false,
    });
    $('.gzzGenerateModal').on('hidden.bs.modal', function (e) {
      $(this).remove();
      if( typeof that.successCallback != 'undefined' ){
        that.successCallback();
      }
    });
  }

  that.closeModal = function( ) {
    $('.gzzGenerateModal').modal('hide');
  }


  that.initModal();
}
