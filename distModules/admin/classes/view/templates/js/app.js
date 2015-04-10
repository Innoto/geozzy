

var app = app || {};


$( document ).ready(function() {



  app = {
    // data
    categories: new CategoryCollection(),

    router: false,
    mainView: false
  }
  

  // Multiple data fetch
  $.when( app.categories.fetch() ).done(function() {
    app.router = new AdminRouter();
    app.mainView = new AdminView();
    Backbone.history.start();
  });



});

