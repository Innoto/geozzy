var app = app || {};

var AdminView = Backbone.View.extend({
  el : $("#wrapper"),

  ajaxContentUrl: false,

  initialize: function(){

  },


  render: function(  ) {



    $("#page-wrapper").load( this.ajaxContentUrl );


  },

  loadAjaxContent: function( url, menuSection ) {
    this.ajaxContentUrl = url;
    this.render();
  }




});