
var app = app || {};

var AdminRouter = Backbone.Router.extend({

  routes: {
    "" : "charts",
    "charts" : "charts",
    "category/:id" : "categoryEdit",
    "category/:category/term/create" : "categoryNewTerm",
    "category/:category/term/edit/:term" : "categoryEditTerm",
    "user/list" : "userList",
    "user/create" : "userCreate",
    "user/edit/:id" : "userEdit",
    "user/show" : "userShow",
    "role/list" : "roleList",
    "role/create" : "roleCreate",
    "role/edit/:id" : "roleEdit",
    "topic/:id" : "resourceintopicList",
    "resource/list": "resourceList",
    "resourceintopic/list": "resourceintopicList",
    "resourceouttopic/list": "resourceouttopicList",
    "resource/create" : "resourceCreate",
    "resource/edit/:id" : "resourceEdit"

  },

  // charts

  charts: function() {
    app.mainView.loadAjaxContent( '/admin/charts' );
  },

  categoryEdit: function( id ){
    app.mainView.categoryEdit( id );
  },

  categoryNewTerm: function( category ){
    app.mainView.loadAjaxContent( '/admin/category/'+category+'/term/create');
  },

  categoryEditTerm: function( category, term ){
    app.mainView.loadAjaxContent( '/admin/category/'+category+'/term/edit/'+term);
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
  },

  // resources
  resourceList: function() {
    app.mainView.loadAjaxContent( '/admin/resource/list');
  },

  resourceintopicList: function() {
    app.mainView.loadAjaxContent( '/admin/resourceintopic/'+id+'/list');   
  },

  resourceouttopicList: function() {
    app.mainView.loadAjaxContent( '/admin/resourceouttopic/list');   
  },


  resourceCreate:function() {
    app.mainView.loadAjaxContent( '/admin/resource/create');
  },

  resourceEdit:function( id )   {
    app.mainView.loadAjaxContent( '/admin/resource/edit/' + id);
  }
});
