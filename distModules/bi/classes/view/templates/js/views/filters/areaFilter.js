//### Area Filter View: Manages the filter view by Geographic Bound
define([
    'backbone',
    'mustache',
    'text!templates/filters/areaFilter.html',
    'collections/filters/filters',
    'config/appConfig'
], function (Backbone, Mustache, AreaFilterTemplate, FiltersCollection, Config) {
    // Creating AreaFilter template, used for filtering by map zone
    var AreaFilter = Backbone.View.extend({
        tagName: 'div',
        events: {
            'click #close': 'remove'
        },
        // Rendering the AreaFilter template, and returns that element
        render: function () {
            var rendered = Mustache.render(AreaFilterTemplate, this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        // Renders the Map Layer
        renderMap: function () {
            var baseLayer = L.tileLayer(Config.URL_OPENSTREETMAP, {
                minZoom: 2,
                maxZoom: 18
            });
            this.map = L.map('mapFilter', {
                layers: [baseLayer]
            });
            this.map.setView([42.8802049, -8.5447697], 2);
            var areaSelect = L.areaSelect({width: 50, height: 50});
            areaSelect.addTo(this.map);

            var that = this;
            areaSelect.on("change", function () {
                var rectangle = L.rectangle(this.getBounds()),
                    coordinates = [];
                // For each element on the area, push their coordinates in a list, and then creates a polygon which is setted into the model
                _.each(rectangle._latlngs, function (latlngs) {
                    coordinates.push([latlngs.lat, latlngs.lng]);
                });
                var values = {
                    '$polygon': coordinates
                };
                that.model.set("values", [values]);
            });
        },
        // Removes the filter
        remove: function () {
            var filterID = this.model.attributes.filterID,
                filterFound = FiltersCollection.findWhere({filterID: filterID});
            FiltersCollection.remove(filterFound);
            this.$el.remove();
        }
    });
    return AreaFilter;
});