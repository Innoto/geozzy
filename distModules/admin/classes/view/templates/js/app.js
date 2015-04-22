

var app = app || {};


$( document ).ready(function() {

  app = {
    // data
    categories: new CategoryCollection(),
    topics: new TopicCollection(),

    router: false,
    mainView: false
  }


  // Multiple data fetch
  $.when( app.categories.fetch(), app.topics.fetch() ).done(function() {
    app.router = new AdminRouter();
    app.mainView = new AdminView();
    Backbone.history.start();

  });



});

