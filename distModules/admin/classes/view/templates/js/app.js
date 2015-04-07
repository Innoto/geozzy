

var app = app || {};

var AdminRouter = Backbone.Router.extend({

  routes: {
    "modaba" : "modaba"
  },

  modaba: function(){
    alert('modaba')
  },
});

 
$( document ).ready(function() {
	new AdminRouter();
	Backbone.history.start();
});

