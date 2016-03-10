var geozzy = geozzy || {};


geozzy.userSession = function() {

  var that = this;
  that.userSession = false;

  //
  // First Execution
  //
  that.userControlAccess = function( callback ) {
    that.getUserSession(
      function(){
        if( that.userSession.get('id') === false ){
          that.initLoginBox(callback);
        }else{
          callback();
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

  that.initLoginBox = function(){

  }
}


/*
var u = new geozzy.userSession();
u.userControlAccess( function(){
  alert('access');
});
*/
