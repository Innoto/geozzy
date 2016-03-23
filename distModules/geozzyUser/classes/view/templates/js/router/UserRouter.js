var geozzy = geozzy || {};
if (! geozzy.userSessionComponents) { geozzy.userSessionComponents= {} }
geozzy.userSessionComponents.mainRouter = Backbone.Router.extend({
  userSessionParent: false,
  routes: {
    'geozzyuser/myprofile': 'myprofile'
  },

  myprofile: function( ) {
    var that = this;
    that.userSessionParent.userControlAccess( function(){
      alert('myprofile');
    });
  }

});
