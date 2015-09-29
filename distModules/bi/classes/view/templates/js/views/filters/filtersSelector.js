define([
    'jquery',
    'backbone',
    'mustache',
    'models/filters/filter',
    'text!templates/filters/filtersSelector.html'
], function($,Backbone,Mustache,FilterModel,filtersTemplate){

    var FiltersView = Backbone.View.extend({
        events: {
            'click .filterSelectorItem': 'filterClicked'
        },
        render: function(){
            var rendered = Mustache.render(filtersTemplate,this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        filterClicked: function(e){
            var filterID = parseInt($(e.target).attr("filterID")),
                filterFound = this.collection.findWhere({filterID:filterID});
            if (!_.isNaN(filterID) && !_.isUndefined(filterID) && _.isUndefined(filterFound)){
                var filter = new FilterModel({ filterID: filterID});
                this.collection.add(filter);
            }
        }
    });
    return FiltersView;
});
