var geozzy = geozzy || {};


geozzy.comment = function() {

  var that = this;
  that.createCommentView = false;
  that.commentOkView = false;
  that.listCommentView = false;
  that.adminListCommentView = false;

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
        that.listCommentView = new geozzy.commentComponents.ListCommentView(idResource);
    }
  }
  that.adminListComment = function( idResource ){
    if(idResource){
        that.adminListCommentView = new geozzy.commentComponents.AdminListCommentView(idResource);
    }
  }
}
