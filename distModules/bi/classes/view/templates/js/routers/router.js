'use strict';

define([
    'backbone',
    'views/app'
], function(Backbone,AppView){
    var Router = Backbone.Router.extend({
        routes: {
            'charts': 'refresh'
        },
        refresh: function(){
        
        }
    });
    return Router;
});