'use strict';

define([
    'jquery',
    'underscore',
    'backbone',
    'mustache',
    'text!templates/statistics/detailBy.html'
],function($,_,Backbone,Mustache,DetailByTemplate){

    var DetailByView = Backbone.View.extend({
        events: {
            'change #detailBySelect': 'detailByChanged'
        },
        render: function(){
            var rendered = Mustache.render(DetailByTemplate,this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        detailByChanged: function(e){
            var detailByID = e.target.value;
            if (!_.isUndefined(detailByID)){
                this.model.set('selectedDetailBy',detailByID);
            }
        }
    });
    return DetailByView;
});