'use strict';

define([
    'underscore',
    'backbone',
    'config/appConfig'
],function(_,Backbone,Config){
    var Explorers = Backbone.Model.extend({
        url : Config.URL_EXPLORERS,
        defaults: {
            elements: []
        },
        parse: function(res){
            var keys = _.keys(res),
                values = _.values(res);
            var content = [];
            _.each(values,function(value,index){
                content.push({
                    id: keys[index],
                    name: value.name
                });
            });
            this.set('elements',content)
        }
    });
    return Explorers
});
