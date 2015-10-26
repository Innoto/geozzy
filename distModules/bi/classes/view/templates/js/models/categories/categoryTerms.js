define([
    'jquery',
    'underscore',
    'backbone',
    'q',
    'config/appConfig'
], function($,_,Backbone,q,Config){
    var CategoryTerms = Backbone.Model.extend({
        defaults: {
            filterID: '',
            values: [],
            title: ''
        },
        getTerms: function(){
            var deferred = q.defer();
            var promises = [],
                categoriesResult = [],
                elementsResult = [];
            $.get(Config.URL_CATEGORY)
                .done(function(categories){
                    _.each(categories,function(category){
                        var url = Config.URL_CATEGORY_TERMS + category.id;
                        categoriesResult.push({
                            id: category.id,
                            name: category.name_es
                        });
                        promises.push($.get(url));
                    });
                    q.allSettled(promises)
                        .then(function(promiseResults) {
                            _.each(promiseResults, function(promiseResult,index){
                                var category = categoriesResult[index];
                                _.each(promiseResult.value,function(term){
                                    elementsResult.push({
                                        categoryID: category.id,
                                        id: term.id,
                                        name: term.name_es,
                                        categoryTermName: category.name + " -> " + term.name_es
                                    });
                                });
                            });
                            deferred.resolve({
                                categories: categoriesResult,
                                elements: elementsResult
                            });
                        })
                        .fail(function(){
                            deferred.reject();
                        });
                })
                .fail(function(){
                    deferred.reject();
                });
            return deferred.promise;
        }
    });
    return CategoryTerms;
});
