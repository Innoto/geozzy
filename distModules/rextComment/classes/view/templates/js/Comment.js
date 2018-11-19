var geozzy = geozzy || {};


geozzy.comment = function() {

  var that = this;
  that.createCommentView = false;
  that.commentOkView = false;
  that.listCommentView = false;
  that.adminListCommentView = false;
  that.userCallBack = function(){};
  // First Execution
  //
  that.createComment = function( idResource, commentType ) {
    if(idResource){
      setTimeout(function() {
        that.createCommentView = new geozzy.commentComponents.CreateCommentView({idResource:idResource, commentType:commentType });
      }, 500);
    }
  };
  that.successCommentBox = function( idResource ){
    that.createCommentView.closeCreateCommentModal();
    that.initCommentOk();
    that.listComment( idResource );
    if(geozzy.biMetricsInstances){
      geozzy.biMetricsInstances.resource.eventCommented( idResource, 'Resource comments' );
    }

  };
  that.initCommentOk = function (){
    setTimeout(function() {
      that.commentOkView = new geozzy.commentComponents.CommentOkView();
    }, 500);
  };

  that.listComment = function( idResource ){
    if(idResource){
      that.listCommentView = new geozzy.commentComponents.ListCommentView(idResource);
      that.listCommentView.commentAppParent = that;
    }
  };

  that.adminListComment = function( idResource ){
    if(idResource){
      that.adminListCommentView = new geozzy.commentComponents.AdminListCommentView(idResource);
    }
  };
  that.setUserCallback = function( userCallBack ){
    that.userCallBack = userCallBack;
  };
};
