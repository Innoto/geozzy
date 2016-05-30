//### Filter Collection: used for storing the every filters used at the same time
define([
    'backbone',
    '../../models/filters/filter'
], function(Backbone,FilterModel){
    // Returns the Filter Collection
    var FiltersCollection = Backbone.Collection.extend({
        model: FilterModel
    });
    return new FiltersCollection();
});
