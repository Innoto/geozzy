var geozzy = geozzy || {};


geozzy.userSession = function() {

  var that = this;
  that.userSession = false;
  that.loginView = false;
  that.finishCallback = false;
  //
  // First Execution
  //
  that.userControlAccess = function(callback) {
    that.finishCallback = callback;
    that.getUserSession(
      function(){
        if( that.userSession.get('id') === false ){
          that.initLoginBox();
        }else{
          that.finishCallback();
        }
      }
    );
  }

  that.getUserSession = function(success){
    that.userSession = new geozzy.model.UserSessionModel();
    that.userSession.fetch({
      success: function(){
        success();
      }
    });
  }

  that.initLoginBox = function(  ){
    that.loginView = new geozzy.user.userLoginView();
  }
  that.successLoginBox = function(){
    that.loginView.closeLoginModal();
    that.finishCallback();
  }
}


/*
var userSession = new geozzy.userSession();
userSession.userControlAccess( function(){
  alert('access');
});
*/
