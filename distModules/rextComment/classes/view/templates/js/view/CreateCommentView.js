
var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.CreateCommentView = Backbone.View.extend({
  commentFormTemplate : false,
  modalTemplate : false,
  idResource: false,
  commentType : false,
  events: {

  },

  initCreateCommentModal: function(){
    var that = this;

    $('body').append( that.modalTemplate({ 'modalId': 'createCommentModal', 'modalTitle': 'Make comment' }) );
    $("#createCommentModal .modal-body").html( that.commentFormTemplate() );
    var urlForm = '';
    if(that.commentType){
      urlForm = '/comment/form/resource/'+that.idResource+'/commenttype/'+that.commentType;
    }else{
      urlForm = '/comment/form/resource/'+that.idResource;
    }
    $("#createCommentModal .modal-body .commentFormModal").load( urlForm );
    $("#createCommentModal").modal({
      'show' : true,
      'keyboard': false,
      /*'backdrop' : 'static'*/
    });
    $("#createCommentModal").on('hidden.bs.modal', function (e) {
      e.target.remove();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
    });
    that.el = "#createCommentModal";
    that.$el = $(that.el);
    that.delegateEvents();
  },
  closeCreateCommentModal: function() {
    var that = this;
    $("#createCommentModal").modal('hide');
  },
  initialize: function( opt ) {
    var that = this;
    that.idResource = opt.idResource;
    that.commentType = opt.commentType;
    that.commentFormTemplate = _.template( geozzy.commentComponents.commentFormTemplate );
    that.modalTemplate = _.template( geozzy.commentComponents.modalMdTemplate );
    that.initCreateCommentModal();
  },
  render: function() {
    var that = this;
    //that.$el.html( that.tpl({ content: contentHtml }) );
  }

});
