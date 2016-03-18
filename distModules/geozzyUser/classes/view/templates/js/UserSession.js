var geozzy = geozzy || {};


geozzy.userSession = function() {

  var that = this;
  that.user = false;
  that.loginView = false;
  that.finishCallback = false;
  //
  // First Execution
  //
  that.userControlAccess = function(callback) {
    that.finishCallback = callback;
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

  that.initLoginBox = function(  ){
    that.loginView = new geozzy.userSessionComponents.userLoginView();
  }
  that.successLoginBox = function(){
    that.loginView.closeLoginModal();
    that.finishCallback();
  }
}
