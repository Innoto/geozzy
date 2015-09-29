define([
    'jquery',
    'underscore',
    'backbone',
    'config/appConfig'
], function($,_,Backbone,Config){

    var MapView = Backbone.View.extend({
        el: '#result',
        initialize: function(options){
            if (_.size(this.$('#map')) === 0){
                this.$el.append('<div id=\'map\'></div>');
            }
            this.$map = this.$('#map');

            this.data = options.chartData;
            this.groupColors = [];
            this.bounds = [];
        },
        getRandomColor: function(){
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        },
        render: function(){
            var baseLayer = L.tileLayer(
                Config.URL_OPENSTREETMAP,{
                    minZoom: 2,
                    maxZoom: 18
                }
            );
            this.map = new L.Map(this.$map[0], {
                layers: [baseLayer]
            });
            this.renderPoints();
            this.renderLegend();
            this.map.fitBounds(this.bounds);
        },
        renderPoints: function(){
            console.log('Number of points to render: '+_.size(this.data));
            var that = this;
            _.each(this.data, function(elementData){
                var bounds = elementData.group.bounds,
                    groupName = elementData.group.groupName;
                if (_.size(bounds[0]) == 2 &&
                    _.size(bounds[1]) == 2 &&
                    !_.isUndefined(groupName)){

                    that.bounds.push(bounds[0],bounds[1]);
                    var randomColor,
                        groupColorEl = _.findWhere(that.groupColors ,{groupName:groupName});
                    if (!_.isUndefined(groupColorEl)){
                        randomColor = groupColorEl.color;
                    }else{
                        randomColor = that.getRandomColor();
                        that.groupColors.push({
                            groupName: groupName,
                            color: randomColor
                        });
                    }
                    var p1 = L.latLng(bounds[0]),
                        p2 = L.latLng(bounds[1]),
                        center = L.latLngBounds(elementData.group.bounds).getCenter(),
                        distance = (p1.distanceTo(p2)/10000).toFixed();
                    L.circle(center, distance, {color:randomColor}).addTo(that.map);
                }
            });
        },
        renderLegend: function(){
            var legend = L.control({position: 'bottomright'});

            var that = this;
            legend.onAdd = function () {
                var div = L.DomUtil.create('div', 'info legend');

                for (var i = 0; i < that.groupColors.length; i++) {
                    var color = that.groupColors[i].color,
                        groupName = that.groupColors[i].groupName;
                    div.innerHTML +=
                        '<i style="background:' + color + '"></i> ' + groupName + '<br>';
                }
                return div;
            };
            legend.addTo(this.map);
        },
        remove: function(){
            this.groupColors = [];
            this.bounds = [];
            this.$map.remove();
        }
    });
    return MapView;
});
