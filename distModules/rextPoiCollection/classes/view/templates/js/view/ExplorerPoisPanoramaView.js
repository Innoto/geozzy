var geozzy = geozzy || {};
geozzy.explorerComponents = geozzy.explorerComponents || {}; //if(!geozzy.explorerComponents) geozzy.explorerComponents={};


geozzy.explorerComponents.panoramaView = Backbone.View.extend({

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
      containerDivId: 'panoramaView',
      panoramaImage: false,
      haov: 360,
      vaov: 70.15
    });

    that.options = $.extend(true, {}, options, opts);
  },


  setParentExplorer: function( parentExplorer ) {
    var  that = this;

    that.parentExplorer = parentExplorer;
  },

  render: function() {
    var that =this;




    if( that.panorama == false ) {

      var hotSpots = [];


      hotSpots.push({
        "pitch": 0,
        "yaw": 0,
        "cssClass": "panorama-custom-hotspot",
        "createTooltipFunc": that.renderSpot,
        "createTooltipArgs": {lol:'asdf'}
      });

      that.panorama = pannellum.viewer( that.options.containerDivId , {
          "autoLoad": true,
          "mouseZoom":false,
          "showFullscreenCtrl": false,
          "type": "equirectangular",
          "panorama": that.options.panoramaImage,

          "haov": that.options.haov,
          "vaov": that.options.vaov,
          "vOffset": 10,

          "hotSpots": hotSpots
        }

      );
      $('#'+that.options.containerDivId).show();

    }


    that.parentExplorer.triggerEvent('onRenderListComplete',{});
  },

  renderSpot: function(hotSpotDiv, args) {
      hotSpotDiv.classList.add('custom-tooltip');
      var span = document.createElement('span');

      $(hotSpotDiv).on('mouseover', function(){
        $(hotSpotDiv).addClass('panorama-custom-hotspot-selected');
        console.log( args );
      });
      $(hotSpotDiv).on('mouseout', function(){
        $(hotSpotDiv).removeClass('panorama-custom-hotspot-selected');
        console.log( args );
      });
  }



/*
  ,


  resourceClick: function( element ) {
    var that = this;
    if(!that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'click');
    }
  },

  resourceHover: function( element ) {
    var that = this;
    if(!that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'mouseenter');
    }
  },

  resourceOut: function( element ) {
    var that = this;
    if(!that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'mouseleave');
    }

  }
*/
});
