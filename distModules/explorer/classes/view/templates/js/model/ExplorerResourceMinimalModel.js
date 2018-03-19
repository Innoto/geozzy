var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }

geozzy.explorerComponents.resourceMinimalModel = Backbone.Model.extend({
  defaults: {
    id: false,
    type: false,
    lat: false,
    lng: false,
    mapVisible: 3,
    mapMarker: false,
    distanceToCenterKm:0,
    img: false
  }

});
