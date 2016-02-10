//### Util file for choosing the correct model by Organization ID
'use strict';

define([
    'underscore',
    'q',
    'models/categories/categoryTerms',
    'models/explorers/explorers',
    'models/topics/topics',
    'config/appConfig'
], function ($, _, q, CategoryTerms, Explorers, Topics, Config) {

    // Creating different lists with each type of: Explorer, Terms and Topics
    var listExplorer = [Config.GROUP_BY_EXPLORER, Config.GROUP_BY_EXPLORER_BOUNDS],
        listTerms = [Config.GROUP_BY_RESOURCE_TERM, Config.GROUP_BY_FILTER, Config.GROUP_BY_FILTER_BOUNDS],
        listTopics = [Config.GROUP_BY_RESOURCE_TOPIC];

    //Returns a promise with the model request
    var getPromise = function (model) {
        var deferred = q.defer();
        model.fetch({
            success: function () {
                deferred.resolve(model.get('elements'));
            },
            error: function () {
                deferred.reject();
            }
        });
        return deferred.promise;
    };
    // Search the Organization ID into the different lists for getting the correct model to use
    // and returns a promise
    return {
        getIDModel: function (orgID) {
            if (_.contains(listExplorer, orgID)) {
                return getPromise(new Explorers());
            }
            if (_.contains(listTopics, orgID)) {
                return getPromise(new Topics());
            }
            if (_.contains(listTerms, orgID)) {
                var categoryTerms = new CategoryTerms();
                return categoryTerms.getTerms();
            }
            var deferred = q.defer();
            deferred.resolve();
            return deferred.promise;
        }
    }
});