

var app = app || {};

 if( window.location.pathname != '/admin/login') {
  // Catch all ajax denied
  $.ajaxSetup({
      statusCode: {
          401: function() {
              window.location.replace('/admin/login');
          }
      }
  });
 }


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

