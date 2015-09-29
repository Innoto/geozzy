define([
    'jquery',
    'underscore',
    'backbone',
    'config/appConfig'
], function($,_,Backbone,Config){

    var HeatMapView = Backbone.View.extend({
        el: '#result',
        initialize: function(options){
            if (_.size(this.$('#map')) === 0){
                this.$el.append('<div id=\'map\'></div>');
            }
            this.$map = this.$('#map');

            this.data = options.chartData;
            this.bounds = [];
        },
        render: function(){
            this.renderMap();
            this.map.fitBounds(this.data);
        },
        renderMap: function(){
            var baseLayer = L.tileLayer(
                Config.URL_OPENSTREETMAP,{
                    minZoom: 2,
                    maxZoom: 18
                }
            );
            var cfgHeatMap = {
                "radius": .1,
                "maxOpacity": .5,
                "scaleRadius": true,
                "useLocalExtrema": true,
                latField: 'lat',
                lngField: 'lng',
                valueField: 'data'
            };
            var heatmapLayer = new HeatmapOverlay(cfgHeatMap);
            this.map = new L.Map(this.$map[0], {
                center: new L.LatLng(this.data[0].lat, this.data[0].lng),
                zoom: 10,
                layers: [baseLayer, heatmapLayer]
            });
            var heatmapData = {
                max: 20,
                data: this.data
            };
            heatmapLayer.setData(heatmapData);
        },
        remove: function(){
            this.$map.remove();
        }
    });
    return HeatMapView;
});
