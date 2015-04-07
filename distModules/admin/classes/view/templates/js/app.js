

var app = app || {};


$( document ).ready(function() {

  app = {
		router : new AdminRouter(),
    mainView : new AdminView()
	}	
	
	Backbone.history.start();
});

