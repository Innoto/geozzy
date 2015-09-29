define([
    'backbone',
    '../../models/filters/filter'
], function(Backbone,FilterModel){

    var FiltersCollection = Backbone.Collection.extend({
        model: FilterModel
    });
    return new FiltersCollection();
});
