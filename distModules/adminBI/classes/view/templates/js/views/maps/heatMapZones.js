//### HeatMap Zones View: Manages the view by Geographic Bound for the explorers
define([
    'underscore',
    'backbone',
    'config/appConfig'
], function (_, Backbone, Config) {
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
                //* Pushing the Explorers List with his new gradient
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
        },
        // Process the assigned data
        //* For each element on the data, their stats are picked
        processData: function () {
            var that = this;
            _.each(this.data, function (elementData) {
                var groupName = elementData.group;
                if (!_.isUndefined(groupName)) {
                    var centerList = _.map(elementData.bounds,function(bounds){
                        var center = L.latLngBounds(bounds).getCenter();
                        return [
                            center.lat,
                            center.lng
                        ];
                    });
                    var centerListSize = _.size(centerList);
                    var bounds = [];
                    _.each(centerList,function(centerListEl){
                        var occurrences = _.filter(centerList,function(innerCenterListEl){
                            return innerCenterListEl[0] == centerListEl[0] && innerCenterListEl[1] == centerListEl[1];
                        });
                        centerListEl.push(_.size(occurrences)/centerListSize);
                        bounds.push(centerListEl);
                    });
                    that.explorers.push({
                        explorerName: groupName,
                        gradient: that.getRandomGradient(groupName),
                        bounds: bounds
                    });
                }
            });
            cogumelo.log(that.explorers);
        },
        // Renders the Map Layer. Specifies the min and max zoom values
        renderMap: function () {
            var baseLayer = L.tileLayer(
                Config.URL_OPENSTREETMAP, {
                    minZoom: 2,
                    maxZoom: 16
                }
            );
            //* If there is no data to show, is rendered a big world map empty
            if (_.size(this.data) < 1) {
                this.map = new L.Map(this.$map[0], {
                    center: new L.LatLng(30, -10),
                    zoom: 2,
                    layers: [baseLayer]
                });
            //* If there is some data, is rendered and centered on the first bounds element
            } else {
                this.map = new L.Map(this.$map[0], {
                    center: new L.LatLng(Config.DEFAULT_MAP_CENTER_LAT,Config.DEFAULT_MAP_CENTER_LNG),
                    zoom: Config.DEFAULT_MAP_ZOOM,
                    layers: [baseLayer]
                });
            }
        },
        // Renders the Heat Layers. Configuring the Heat Zone for each different explorer
        renderHeatMapLayers: function () {
            var that = this;
            for (var i = 0; i < this.explorers.length; i++) {
                var explorer = this.explorers[i];
                var cfgHeatMap = {
                    maxZoom: 12,
                    minOpacity: .8,
                    max: 1,
                    blur: 50,
                    //* Sets a random gradient to each explorer
                    gradient: that.getRandomGradient(explorer.explorerName)
                };
                //* Adds this Layer to the Map Layer
                L.heatLayer(
                    explorer.bounds,
                    cfgHeatMap
                ).addTo(that.map);
            }
        },
        // Renders the Legend. Creates the DOM element for the Legend
        renderLegend: function () {
            var legend = L.control({position: 'bottomleft'});
            var that = this;

            legend.onAdd = function () {
                var div = L.DomUtil.create('div', 'info legend');
                //* For each different explorer, it creates a new Legend Element, with his name and gradient
                _.each(that.explorers, function (explorer) {
                    var explorerName = explorer.explorerName,
                        gradient = _.values(explorer.gradient);
                    div.innerHTML +=
                        explorerName + "<i style='background: linear-gradient(-45deg," + gradient[1] + "," + gradient[0] + ")'></i><br/>";
                });
                return div;
            };
            //* Adds this Layer to the Map Layer
            legend.addTo(this.map);
        },
        // Removes the map, explorers, gradients and bounds
        remove: function () {
            this.explorers = [];
            this.gradientExplorers = [];
            this.$map.remove();
        }
    });
    return MapView;
});
