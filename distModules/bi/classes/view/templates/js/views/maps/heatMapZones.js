//### HeatMap Zones View
define([
    'jquery',
    'underscore',
    'backbone',
    'config/appConfig'
], function ($, _, Backbone, Config) {
    // Creating Map view, used for showing the Explored Zones Map when we organize by Geographic Bound
    var MapView = Backbone.View.extend({
        el: '#result',
        // Initializes the map element, with his explorers (and his gradients), and the bounds
        initialize: function (options) {
            if (_.size(this.$('#map')) === 0) {
                this.$el.append('<div id=\'map\'></div>');
            }
            this.$map = this.$('#map');

            this.data = options.chartData;
            this.explorers = [];
            this.gradientExplorers = [];
            this.bounds = [];
        },
        // Sets hexadecimal random color, used for the gradient
        getRandomColor: function () {
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        },
        // Specifies a random color for each explorer in the map to show
        getRandomGradient: function (explorerName) {
            var gradientExplorer = _.findWhere(this.gradientExplorers, {explorerName: explorerName});
            if (_.isUndefined(gradientExplorer)) {
                var newGradientExplorer = {
                    explorerName: explorerName,
                    gradient: {
                        0.55: this.getRandomColor(),
                        0.75: this.getRandomColor()
                    }
                };
                // Pushing the Explorers List with his new gradient
                this.gradientExplorers.push(newGradientExplorer);
                return newGradientExplorer.gradient;
            } else {
                return gradientExplorer.gradient;
            }
        },
        // Calls for process the Data, rendering the both layers: the map and the heat layer, and the Legend
        render: function () {
            this.processData();
            this.renderMap();
            this.renderHeatMapLayers();
            this.renderLegend();
            this.map.fitBounds(this.bounds);
        },
        // Process the assigned data
        processData: function () {
            var that = this;
            // For each element on the data, their stats are picked
            _.each(this.data, function (elementData) {
                var data = elementData.data,
                    bounds = elementData.group.bounds,
                    groupName = elementData.group.groupName;
                // Controls if each element has Lat and Lng in their bounds and is not Undefined
                if (_.size(bounds[0]) == 2 &&
                    _.size(bounds[1]) == 2 && !_.isUndefined(groupName)) {
                    // Picks the explorer position
                    var explorer = _.findWhere(that.explorers, {explorerName: groupName}),
                        center = L.latLngBounds(bounds).getCenter();
                    var bounds = [
                        center.lat,
                        center.lng
                    ];
                    that.bounds.push(bounds);
                    bounds.push(data);
                    // If the explorer is Undefined, is pushed in the list with his data
                    if (_.isUndefined(explorer)) {
                        that.explorers.push({
                            explorerName: groupName,
                            gradient: that.getRandomGradient(groupName),
                            bounds: [bounds]
                        });
                        // If is not Undefined, his index is picked from the list, and their bounds are pushed
                    } else {
                        var index = _.indexOf(that.explorers, explorer);
                        that.explorers[index].bounds.push(bounds);
                    }
                }
            });
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
                // If there is some data, is rendered and centered on the first bounds element
            } else {
                this.map = new L.Map(this.$map[0], {
                    center: new L.LatLng(this.explorers[0].bounds[0][0], this.explorers[0].bounds[0][1]),
                    zoom: 10,
                    layers: [baseLayer]
                });
            }
        },
        // Renders the Heat Layers
        renderHeatMapLayers: function () {
            var that = this;
            // Configuring the Heat Zone for each different explorer
            _.each(this.explorers, function (explorer) {
                var cfgHeatMap = {
                    maxZoom: 12,
                    minOpacity: .8,
                    max: .4,
                    blur: 50,
                    // Sets a random gradient to each explorer
                    gradient: that.getRandomGradient(explorer.explorerName)
                };
                // Adds this Layer to the Map Layer
                L.heatLayer(
                    explorer.bounds,
                    cfgHeatMap
                ).addTo(that.map);
            });
        },
        // Renders the Legend
        renderLegend: function () {
            var legend = L.control({position: 'bottomleft'});

            var that = this;
            // Creates the DOM element for the Legend
            legend.onAdd = function () {
                var div = L.DomUtil.create('div', 'info legend');
                // For each different explorer, it creates a new Leyend Element, with his name and gradient
                _.each(that.explorers, function (explorer) {
                    var explorerName = explorer.explorerName,
                        gradient = _.values(explorer.gradient);
                    div.innerHTML +=
                        explorerName + "<i style='background: linear-gradient(-45deg," + gradient[1] + "," + gradient[0] + ")'></i><br/>";
                });
                return div;
            };
            // Adds this Layer to the Map Layer
            legend.addTo(this.map);
        },
        // Removes the map, explorers, gradients and bounds
        remove: function () {
            this.explorers = [];
            this.gradientExplorers = [];
            this.bounds = [];
            this.$map.remove();
        }
    });
    return MapView;
});
