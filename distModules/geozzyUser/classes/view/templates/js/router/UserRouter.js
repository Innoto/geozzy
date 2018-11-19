var geozzy = geozzy || {};
if (! geozzy.userSessionComponents) { geozzy.userSessionComponents= {}; }
geozzy.userSessionComponents.mainRouter = Backbone.Router.extend({
  userSessionParent: false,
  routes: {
    'user/accessprofile': 'controlAccessProfile',
    'user/profile': 'myprofile'
  },

  controlAccessProfile: function( ) {
    var that = this;
    that.userSessionParent.userControlAccess(
      function(){
        window.location.href="/userprofile#user/profile";
        //that.navigate("/", {trigger: true});
      },
      function(){
        that.navigate("/", {trigger: true});
      }
    );
  },
  myprofile: function() {
    if( window.location.pathname === "/userprofile" ){
      $(".bodyContent").load( "/geozzyuser/profile", {}, function(){} );
    }
  },
  successProfileForm: function() {
    this.navigate( "user/accessprofile", {trigger:true} );
  }
});
