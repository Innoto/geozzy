var app = app || {};

var AdminView = Backbone.View.extend({
  el : $("#wrapper"),

  childView: false,

  initialize: function(){
    this.renderMenu();
  },

  renderMenu: function(  ){
    //Categories
    var menuCategoryElement =  _.template($("#menuCategoryElement").html());
    var menuCategoriesDiv = $('#wrapper .navbar .navbar-default.sidebar .categoriesList');
    menuCategoriesDiv.html( menuCategoryElement( { categories:  app.categories.toJSON()  } ) );
    //Topics
    var menuTopics =  _.template($("#menuTopics").html());
    var menuTopicsContainer = $('#wrapper #side-menu');
    menuTopicsContainer.prepend( menuTopics( { topics:  app.topics.toJSON()  } ) );
  },

  categoryEdit: function( id ) {
    this.childView = new CategoryEditorView( app.categories.get(id) );
    this.render(  );
  },

  loadAjaxContent: function( url ) {
    this.childView = false;
    $("#page-wrapper").load( url );
    this.render();
  },

  render: function( ) {
    if( this.childView != false ) {
      $("#page-wrapper").undelegate();
      this.childView.$el = $('#page-wrapper');
      this.childView.render();
      this.childView.delegateEvents();
    }
  },

  // effects
  menuSelect: function( menuClass ) {
    $('#side-menu *').removeClass('active');
    $('#side-menu .'+menuClass+' a').addClass('active');

    $('.navbar-collapse').collapse('hide');

  }

});
