
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

    "resource/list": "resourceList",
    "topic/:id" : "resourceintopicList",
    "resourceintopic/list/:id": "resourceintopicList",
    "resourceouttopic/list/:id": "resourceouttopicList",
    "starred/:id": "starredList",
    "starred/:id/assign": "starredAssign",
    "resource/create" : "resourceCreate",
    "resource/create/:topic/:resourcetype" : "resourceCreateinTopic",
    "resource/edit/:id" : "resourceEdit",

    "collection/create" : "collectionCreate",
    "collection/edit/:id" : "collectionEdit"

  },

  // charts

  charts: function() {
    app.mainView.loadAjaxContent( '/admin/charts' );
  },

  categoryEdit: function( id ){
    app.mainView.categoryEdit( id );
    app.mainView.menuSelect('category_'+id);

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
    app.mainView.menuSelect('user');
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
    app.mainView.menuSelect('roles');
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
    app.mainView.menuSelect('contents');
  },

  resourceintopicList: function(id) {
    app.mainView.loadAjaxContent( '/admin/resourceintopic/list/'+id);
    app.mainView.menuSelect('topic_'+id);
  },

  resourceouttopicList: function(id) {
    app.mainView.loadAjaxContent( '/admin/resourceouttopic/list/'+id);
  },

  starredList: function( id ){
    app.mainView.starredList( id );
    app.mainView.menuSelect('star_'+id);

  },
  starredAssign: function(id) {
    app.mainView.loadAjaxContent( '/admin/starred/'+id+'/assign');
  },


  resourceCreate:function() {
    app.mainView.loadAjaxContent( '/admin/resource/create');
  },
  resourceCreateinTopic:function( topic, resourcetype) {
    app.mainView.loadAjaxContent( '/admin/resource/create/'+topic+'/'+resourcetype);
  },
  resourceEdit:function( id )   {
    app.mainView.loadAjaxContent( '/admin/resource/edit/' + id);
  },

  collectionCreate:function() {
    app.mainView.loadAjaxContent( '/admin/collection/create');
  },
  collectionEdit:function( id )   {
    app.mainView.loadAjaxContent( '/admin/collection/edit/' + id);
  }

});
