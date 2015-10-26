//### HeatMap View
define([
    'jquery',
    'underscore',
    'backbone',
    'config/appConfig'
], function ($, _, Backbone, Config) {
    // Creating HeatMap view, used for showing the classical Heat Map in the Metrics
    var HeatMapView = Backbone.View.extend({
        el: '#result',
        // Initializes the map element
        initialize: function (options) {
            if (_.size(this.$('#map')) === 0) {
                this.$el.append('<div id=\'map\'></div>');
            }
            this.$map = this.$('#map');

            this.data = options.chartData;
        },
        // Calls for rendering the both layers: the map, and the heat layer
        render: function () {
            this.renderMap();
            this.renderHeatMapLayer();
            this.map.fitBounds(this.data);
        },
        // Renders the Map Layer
        renderMap: function () {
            // Specifies the min and max zoom values
            var baseLayer = L.tileLayer(
                Config.URL_OPENSTREETMAP, {
                    minZoom: 2,
                    maxZoom: 16
                }
            );
            // If there is no data to show, is rendered a big world map empty
            if (_.size(this.data) < 1) {
                this.map = new L.Map(this.$map[0], {
                    center: new L.LatLng(30, -10),
                    zoom: 2,
                    layers: [baseLayer]
                });
                // If there is some data, is rendered and centered on the first element of it
            } else {
                this.map = new L.Map(this.$map[0], {
                    center: new L.LatLng(this.data[0].lat, this.data[0].lng),
                    zoom: 10,
                    layers: [baseLayer]
                });
            }
        },
        // Renders the Heat Layer
        renderHeatMapLayer: function () {
            // Configuring the Heat Map
            var cfgHeatMap = {
                "radius": .005,
                "maxOpacity": .5,
                "scaleRadius": true,
                "useLocalExtrema": true,
                latField: 'lat',
                lngField: 'lng',
                valueField: 'data'
            };
            var heatMapLayer = new HeatmapOverlay(cfgHeatMap);
            // Adding the HeatMap Layer to the Map Layer
            this.map.addLayer(heatMapLayer);
            // Setting the Map Data
            var heatMapData = {
                max: 50,
                data: this.data
            };
            heatMapLayer.setData(heatMapData);
        },
        // Removes the map
        remove: function () {
            this.$map.remove();
        }
    });
    return HeatMapView;
});
