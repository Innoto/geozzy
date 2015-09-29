define([
    'jquery',
    'backbone',
    'mustache',
    'text!templates/filters/boxFilter.html',
    'select2',
    'collections/filters/filters'
], function($,Backbone,Mustache,filtersTemplate,Select2,FiltersCollection){

    var BoxFilter = Backbone.View.extend({
        tagName: 'div',
        events: {
            'click #close': 'remove',
            'change .selectMultiple': 'optionSelected'
        },
        render: function () {
            var rendered = Mustache.render(filtersTemplate, this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        remove: function(){
            var filterID = this.model.attributes.filterID,
                filterFound = FiltersCollection.findWhere({filterID:filterID});
            FiltersCollection.remove(filterFound);
            this.$el.remove();
        },
        optionSelected: function(e){
            var values = $(e.target).val();
            this.model.set("values",values);
            console.log(this.model.get("values"));
        }
    });
    return BoxFilter;
});


