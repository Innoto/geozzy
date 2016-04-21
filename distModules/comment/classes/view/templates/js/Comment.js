var geozzy = geozzy || {};


geozzy.comment = function() {

  var that = this;
  that.createCommentView = false;
  that.createCommentOkView = false;



  // First Execution
  //
  that.createComment = function( idResource, commentType ) {
    that.finishCallback = successCallback;
    if(abortCallback){
      that.abortCallback = abortCallback;
    }
    that.getUserSession(
      function(){
        if( that.user.get('id') === false ){
          that.initLoginBox();
        }else{
          that.finishCallback();
        }
      }
    );
  }

  that.getUserSession = function(success){
    that.user = new geozzy.userSessionComponents.UserSessionModel();
    that.user.fetch({
      success: function(){
        success();
      }
    });
  }

  that.initLoginBox = function(){
    that.loginView = new geozzy.userSessionComponents.userLoginView();
    that.loginView.userSessionParent = that;
  }
  that.successLoginBox = function(){
    that.loginView.closeLoginModal();
    that.finishCallback();
  }

  that.initRegisterBox = function(){
    setTimeout(function() {
      that.registerView = new geozzy.userSessionComponents.userRegisterView();
      that.registerView.userSessionParent = that;
    }, 500);
  }
  that.successRegisterBox = function(){
    that.registerView.closeRegisterModal();
    that.initRegisterOkBox();

  }
  that.initRegisterOkBox = function(){
    setTimeout(function() {
      that.registerOkView = new geozzy.userSessionComponents.userRegisterOkView();
      that.registerOkView.userSessionParent = that;
    }, 500);

    that.registerOkView = new geozzy.userSessionComponents.userRegisterOkView();
    that.registerOkView.userSessionParent = that;
  }

  that.userRouter = new geozzy.userSessionComponents.mainRouter();
  that.userRouter.userSessionParent = that;

  $(document).ready( function(){
    if( !Backbone.History.started ){
      Backbone.history.start();
    }
  });
}
