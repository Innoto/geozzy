var geozzy = geozzy || {};


geozzy.comment = function() {

  var that = this;
  that.createCommentView = false;
  that.commentOkView = false;
  that.listCommentView = false;

  // First Execution
  //
  that.createComment = function( idResource, commentType ) {
    if(idResource){
      setTimeout(function() {
        that.createCommentView = new geozzy.commentComponents.CreateCommentView({idResource:idResource, commentType:commentType });
      }, 500);
    }
  }
  that.successCommentBox = function(){
    that.createCommentView.closeCreateCommentModal();
    that.initCommentOk();
  }
  that.initCommentOk = function (){
    setTimeout(function() {
      that.commentOkView = new geozzy.commentComponents.CommentOkView();
    }, 500);
  }

  that.listComment = function( idResource ){
    if(idResource){
      //geozzy.commentComponents.comments = new CommentCollection([], { id: idResource });
      //$.when( geozzy.commentComponents.comments.fetch() ).done(function() {
        that.listCommentView = new geozzy.commentComponents.ListCommentView(idResource);
      //});
    }
  }
}
