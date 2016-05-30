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
            new AppView();
        }
    });
    return Router;
});