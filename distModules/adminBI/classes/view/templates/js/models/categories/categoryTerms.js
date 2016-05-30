//### Category Terms Model: Used for getting the Terms and their categories
define([
    'underscore',
    'backbone',
    'q',
    'config/appConfig'
], function (_, Backbone, q, Config) {
    // Model with the values for the terms: ID, Values and Title
    var CategoryTerms = Backbone.Model.extend({
        defaults: {
            filterID: '',
            values: [],
            title: ''
        },
        // Return the correct terms. Search by Categories, and push into a list all of the results with the ID and Name.
        // Then, for each Category is going push into another list all of the correspondent Terms with his Attributes.
        getTerms: function () {
            var deferred = q.defer();
            var promises = [],
                categoriesResult = [],
                elementsResult = [];
            $.get(Config.URL_CATEGORY)
                .done(function (categories) {
                    _.each(categories, function (category) {
                        var url = Config.URL_CATEGORY_TERMS + category.id;
                        categoriesResult.push({
                            id: category.id,
                            name: category.name
                        });
                        promises.push($.get(url));
                    });
                    q.allSettled(promises)
                        .then(function (promiseResults) {
                            _.each(promiseResults, function (promiseResult, index) {
                                var category = categoriesResult[index];
                                _.each(promiseResult.value, function (term) {
                                    elementsResult.push({
                                        categoryID: category.id,
                                        id: term.id,
                                        name: term.name,
                                        categoryTermName: category.name + " -> " + term.name
                                    });
                                });
                            });
                            deferred.resolve({
                                categories: categoriesResult,
                                elements: elementsResult
                            });
                        })
                        .fail(function () {
                            deferred.reject();
                        });
                })
                .fail(function () {
                    deferred.reject();
                });
            return deferred.promise;
        }
    });
    return CategoryTerms;
});
