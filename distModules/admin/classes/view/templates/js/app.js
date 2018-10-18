

var app = app || {};
var geozzy = geozzy || {};

function manageAjaxFailures() {
  //if( window.location.pathname != '/admin/login') {
    // Catch all ajax denied
    $.ajaxSetup({
      statusCode: {
        401: function(datos) {
          geozzy.userSessionInstance.getUserSession( function() {
            console.log(geozzy.userSessionInstance.user.get('id') )

            if( geozzy.userSessionInstance.user.get('id') == false ) {
              window.location.replace('/admin/login');
            }
          });

        },
        403: function() {
          geozzy.userSessionInstance.getUserSession( function() {
            if( geozzy.userSessionInstance.user.get('id') == false ) {
              window.location.replace('/admin/login');
            }
          });
        }
      }
    });
  //}
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


  // Multiple data fetch
  $.when( app.categories.fetch(), app.topics.fetch(), app.starred.fetch()).done(function() {
    app.router = new AdminRouter();
    app.mainView = new AdminView();
    if( !Backbone.History.started ){
      Backbone.history.start();
    }
    else {
      Backbone.history.stop();
      Backbone.history.start();
    }

    operatingHeader();
    manageAjaxFailures();
  });


});

function operatingHeader(){
  $(window).bind("load resize", function() {
    calculateHeightMenu();
  });
  calculateHeightMenu();
  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("closed");
  });

  //$('#side-menu').metisMenu();

  $('#side-menu').metisMenu({
    // enabled/disable the auto collapse.
    toggle: true,
    // prevent default event
    preventDefault: true,
    // default classes
    activeClass: 'active',
    collapseClass: 'collapse',
    collapseInClass: 'in',
    collapsingClass: 'collapsing',
    // .nav-link for Bootstrap 4
    triggerElement: 'a',
    // .nav-item for Bootstrap 4
    parentTrigger: 'li',
    // .nav.flex-column for Bootstrap 4
    subMenu: 'ul'
  });
}
function calculateHeightMenu(){
  if($(window).width() > 991){
    var menuInfoHeight = $('#menuInfo').height();
    var windowHeight = $(window).height();
    $('#side-menu').height(windowHeight - menuInfoHeight);
  }
}
