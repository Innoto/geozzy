var app = app || {};

var AdminView = Backbone.View.extend({
  el : $("#wrapper"),

  initialize: function(){
    this.renderMenu();
  },

  categoryEdit: function( id ) {
    console.log(app.categories.get(id).toJSON());
  },



  renderMenu: function(  ){

    var menuCategoryElement =  _.template($("#menuCategoryElement").html());
    var menuCategoriesDiv = $('#wrapper .navbar .navbar-default.sidebar .categoriesList');

    menuCategoriesDiv.html( menuCategoryElement( { categories:  app.categories.toJSON()  } ) );

  },

  loadAjaxContent: function( url ) {
    $("#page-wrapper").load( url );
    this.render();
  },

  render: function( content ) {
    if( content ) {

    }
  },


});