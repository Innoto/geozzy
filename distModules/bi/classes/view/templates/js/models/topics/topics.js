'use strict';

define([
    'jquery',
    'underscore',
    'backbone',
    'config/appConfig'
], function($,_,Backbone,Config){

    var Topics = Backbone.Model.extend({
        url: Config.URL_TOPICS,
        defaults: {
            elements: []
        },
        parse: function(res){
            var content = [];
            _.each(res,function(value){
                content.push({
                    id: value.id,
                    name: value.name_es
                });
            });
            this.set('elements',content);
        }
    });
    return Topics;
});
