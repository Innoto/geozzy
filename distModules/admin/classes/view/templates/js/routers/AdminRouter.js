
var app = app || {};

var AdminRouter = Backbone.Router.extend({

  routes: {
    "" : "charts",
    "charts" : "charts",
    "category/:id" : "categoryEdit",
    "user/list" : "userList",
    "user/create" : "userCreate",    
    "user/edit/:id" : "userEdit",
    "user/show" : "userShow",
    "role/list" : "roleList",     
    "role/create" : "roleCreate",    
    "role/edit/:id" : "roleEdit"

  },

  // charts

  charts: function() {
    app.mainView.loadAjaxContent( '/admin/charts' );
  },

  categoryEdit: function( id ){
    app.mainView.categoryEdit( id );
  },

  // User
  userList: function(){
    app.mainView.loadAjaxContent( '/admin/user/list' );
  },

  userCreate: function( ) {
    app.mainView.loadAjaxContent( '/admin/user/create');    
  },

  userEdit: function( id ) {
    app.mainView.loadAjaxContent( '/admin/user/edit/' + id );    
  },

  userShow: function() {
    app.mainView.loadAjaxContent( '/admin/user/show' );   
  },

  // Roles
  roleList: function(){
    app.mainView.loadAjaxContent( '/admin/role/list' );
  },

  roleCreate: function( ) {
    app.mainView.loadAjaxContent( '/admin/role/create');    
  },

  roleEdit: function( id ) {
    app.mainView.loadAjaxContent( '/admin/role/edit/' + id );    
  }
});
