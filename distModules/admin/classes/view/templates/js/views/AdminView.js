var app = app || {};
var tax = false;
var AdminView = Backbone.View.extend({
  el : $("#wrapper"),

  ajaxContentUrl: false,

  taxonomyGroups: false,

  initialize: function(){
    this.renderMenu();
  },

  renderMenu: function(  ) {
    var categories = new CategoryCollection();

    var menuCategoryElement =  _.template($("#menuCategoryElement").html());
    //var eachCategoryTpl = _.Template()
    var menuCategoriesDiv = $('#wrapper .navbar .navbar-default.sidebar .categoriesList');

    //categories.add( {id:123, idName:'bla'} )
    //alert(categories.size())



    categories.fetch({
      success: function() {

    //console.log( categories.toJSON()  );
    menuCategoriesDiv.html( menuCategoryElement( { categories:  categories.toJSON()  } ) );

      }
    });


  },

  render: function(  ) {
    $("#page-wrapper").load( this.ajaxContentUrl );
  },

  loadAjaxContent: function( url, menuSection ) {
    this.ajaxContentUrl = url;
    this.render();
  }


});