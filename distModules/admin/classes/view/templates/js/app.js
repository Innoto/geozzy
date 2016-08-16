

var app = app || {};
var geozzy = geozzy || {};


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
    starred: new StarredCollection(),

    router: false,
    mainView: false
  }

  /* Só se executa se está o modulo Stories */
  var storiesFetch = true;
  if( typeof geozzy.story !== 'undefined' ) {
    if( typeof geozzy.storiesInstance === 'undefined' ) {
      geozzy.storiesInstance = new geozzy.story();
      geozzy.storiesInstance.listStories();
      storiesFetch = geozzy.storiesInstance.listStoryView.stories.fetch();
    }
  }

  // Multiple data fetch
  $.when( app.categories.fetch(), app.topics.fetch(), app.starred.fetch(), storiesFetch).done(function() {
    app.router = new AdminRouter();
    app.mainView = new AdminView();
    if( !Backbone.History.started ){
      Backbone.history.start();
    }
    else {
      Backbone.history.stop();
      Backbone.history.start();
    }
    // só en caso de módulo Stories activado
    if( typeof geozzy.story !== 'undefined' ) {
      geozzy.storiesInstance.listStoryView.render();
    }
  });

  $(window).bind("load resize", function() {
    calculateHeightMenu();
  });
  calculateHeightMenu();
  $('#side-menu').metisMenu();

});


function calculateHeightMenu(){
  if($(window).width() > 991){
    var menuInfoHeight = $('#menuInfo').height();
    var windowHeight = $(window).height();
    $('#side-menu').height(windowHeight - menuInfoHeight);
  }
}
