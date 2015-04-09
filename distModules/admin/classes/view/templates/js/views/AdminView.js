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
    var cateogories = new CategoryCollection();
/*
    var menuTpl =  _.template($("#adminMenuTemplate").html());
    //var eachCategoryTpl = _.Template()
    var menuDiv = $('#wrapper .navbar .navbar-default.sidebar');

    menuDiv.html( menuTpl() );

    cateogories.fetch({
      success: function() {
        
      }
    });*/


  },

  render: function(  ) {
    $("#page-wrapper").load( this.ajaxContentUrl );
  },

  loadAjaxContent: function( url, menuSection ) {
    this.ajaxContentUrl = url;
    this.render();
  }


});