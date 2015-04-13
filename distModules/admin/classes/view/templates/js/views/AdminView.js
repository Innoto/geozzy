var app = app || {};

var AdminView = Backbone.View.extend({
  el : $("#wrapper"),

  childView: false,

  initialize: function(){
    this.renderMenu();
  },

  categoryEdit: function( id ) {
    //console.log(app.categories.get(id).toJSON());
    this.childView = new CategoryEditorView();
    this.render();
  },



  renderMenu: function(  ){
    var menuCategoryElement =  _.template($("#menuCategoryElement").html());
    var menuCategoriesDiv = $('#wrapper .navbar .navbar-default.sidebar .categoriesList');

    menuCategoriesDiv.html( menuCategoryElement( { categories:  app.categories.toJSON()  } ) );

  },

  loadAjaxContent: function( url ) {
    this.childView = false;
    $("#page-wrapper").load( url );
    this.render();
  },

  render: function( ) {
    if( this.childView != false ) {
      this.childView.$el = $('#page-wrapper');
      this.childView.render();
      this.childView.delegateEvents();
    }
  }


});