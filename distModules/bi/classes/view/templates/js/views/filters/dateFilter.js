define([
    'jquery',
    'backbone',
    'mustache',
    'text!templates/filters/dateFilter.html',
    'select2',
    'collections/filters/filters',
    'datetimepicker'
], function($,Backbone,Mustache,filtersTemplate,Select2,FiltersCollection,DateTimePicker){

    var DateFilter = Backbone.View.extend({
        tagName: 'div',
        events: {
            'click #close': 'remove'
        },
        render: function (){
            var rendered = Mustache.render(filtersTemplate, {});
            this.$el.html(rendered);
            return this;
        },
        remove: function(){
            var filterID = this.model.attributes.filterID,
                filterFound = FiltersCollection.findWhere({filterID:filterID});
            FiltersCollection.remove(filterFound);
            this.$el.remove();
        }
    });
    return DateFilter;
});