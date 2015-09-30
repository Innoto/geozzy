'use strict';

define([
    'backbone'
], function(Backbone){
    var Router = Backbone.Router.extend({
        routes: {
            '*all': 'redirectToRoot'
        },
        redirectToRoot: function(){
            //this.navigate('/');
        }
    });
    return Router;
});
