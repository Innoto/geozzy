var geozzy = geozzy || {};
geozzy.explorerComponents = geozzy.explorerComponents || {};

$(document).ready(function () {

  if (geozzy.rExtPanoramaOptions.showPanorama != false ) {
    var panoramaView = new geozzy.explorerComponents.panoramaView({
      panoramaImage: geozzy.rExtPanoramaOptions.panoramaImg,
      haov: geozzy.rExtPanoramaOptions.horizontalAnglePanorama,
      vaov: geozzy.rExtPanoramaOptions.verticalAnglePanorama
    });
    panoramaView.render();
  }
} );


geozzy.explorerComponents.panoramaView = Backbone.View.extend( {
  tpl: false,
  tplElement: false,

  displayType: 'plugin',
  parentExplorer: false,

  panorama: false,

  events: {
  },

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      containerDivId: 'imgPanoramaView',
      panoramaImage: false,
      haov: 360,
      vaov: 50
    });

    that.options = $.extend( true, {}, options, opts );
  },

  render: function() {
    var that = this;

    if( that.panorama == false ) {

      that.panorama = pannellum.viewer( that.options.containerDivId, {
          "autoLoad": true,
          "mouseZoom": false,
          "showFullscreenCtrl": false,
          "type": "equirectangular",
          "panorama": that.options.panoramaImage,

          "haov": that.options.haov,
          "vaov": that.options.vaov,
          "vOffset": 10
        }
      );

      $('#'+that.options.containerDivId).show();
    }
  }
} );
