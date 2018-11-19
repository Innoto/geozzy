
var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.CommentOkView = Backbone.View.extend({
  commentFormOkTemplate : false,
  modalTemplate : false,
  events: {

  },

  initCommentOkModal: function(){
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'commentOkModal', 'modalTitle': 'Thanks you' }) );
    $("#commentOkModal .modal-body").html( that.commentFormOkTemplate() );
    $("#commentOkModal").modal({
      'show' : true,
      'keyboard': false,
      /*'backdrop' : 'static'*/
    });
    $("#commentOkModal").on('hidden.bs.modal', function (e) {
      e.target.remove();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });
    that.el = "#commentOkModal";
    that.$el = $(that.el);
    that.delegateEvents();
  },
  closeCommentOkModal: function() {
    var that = this;
    $("#commentOkModal").modal('hide');
  },
  initialize: function( ) {
    var that = this;
    that.commentFormOkTemplate = _.template( geozzy.commentComponents.commentFormOkTemplate );
    that.modalTemplate = _.template( geozzy.commentComponents.modalMdTemplate );
    that.initCommentOkModal();
  },
  render: function() {
    var that = this;
    //that.$el.html( that.tpl({ content: contentHtml }) );
  }

});
