//### Organizations View
'use strict';

define([
    'jquery',
    'underscore',
    'backbone',
    'mustache',
    'text!templates/statistics/organizations.html'
], function ($, _, Backbone, Mustache, OrgsTemplate) {
    // Creating Organizations template, used for selecting a Organization
    var OrgsView = Backbone.View.extend({
        // Picking the Change event on the box
        events: {
            'change #organizations': 'changeOrganization'
        },
        // Rendering the Organizations template, and returns that element
        render: function () {
            var rendered = Mustache.render(OrgsTemplate, this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        // Manages the Change event, where is taken the correct value ID if is not Undefined
        changeOrganization: function (e) {
            var organizationID = e.target.value;
            if (!_.isUndefined(organizationID)) {
                this.model.set('selectedOrg', organizationID);
            }
        }
    });
    return OrgsView;
});
