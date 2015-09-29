'use strict';

define([
    'jquery',
    'underscore',
    'backbone',
    'mustache',
    'text!templates/statistics/organizations.html'
],function($,_,Backbone,Mustache,OrgsTemplate){

    var OrgsView = Backbone.View.extend({
        events: {
            'change #organizations': 'changeOrganization'
        },
        render: function (){
            var rendered = Mustache.render(OrgsTemplate,this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        changeOrganization: function(e){
            var organizationID = e.target.value;
            if (!_.isUndefined(organizationID)){
                this.model.set('selectedOrg',organizationID);
            }
        }
    });
    return OrgsView;
});
