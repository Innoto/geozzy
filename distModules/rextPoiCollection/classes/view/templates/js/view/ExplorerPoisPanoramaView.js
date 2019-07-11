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
      haov: geozzy.rExtPOIOptions.horizontalAnglePanorama, /*Default: 360*/
      vaov: geozzy.rExtPOIOptions.verticalAnglePanorama /*Default: 50*/
    });

    that.options = $.extend(true, {}, options, opts);
  },


  setParentExplorer: function( parentExplorer ) {
    var  that = this;

    that.parentExplorer = parentExplorer;



    that.parentExplorer.bindEvent('mapResourceHover', function(ev){
       that.centerInPanorama(ev.id);
    });
    that.parentExplorer.bindEvent('resourceMouseOut', function(ev){
       //that.unShakeHotsPot(ev.id);
    });

  },

  render: function() {
    var that =this;




    if( that.panorama == false ) {

      var hotSpots = [];

      that.parentExplorer.resourceMinimalList.each(function(e,i){

        if( e.get('panoramaPitch') &&  e.get('panoramaYaw') ) {
          hotSpots.push({
            "pitch": parseInt(e.get('panoramaPitch')),
            "yaw": parseInt(e.get('panoramaYaw')),
            "cssClass": "panorama-custom-hotspot",
            "createTooltipFunc": function(a,e) {that.renderSpot(a,e)},
            "createTooltipArgs": { id: e.get('id') }
          });
        }


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
    var that=this;
    var e = that.parentExplorer.resourceMinimalList.get(args.id);
    e.set('hotSpotDiv',hotSpotDiv);


    $(hotSpotDiv).on('mouseover', function(){

      that.parentExplorer.displays.map.markerBounce( args.id );
      that.parentExplorer.displays.map.panTo( args.id );

      that.parentExplorer.triggerEvent('resourceHover', args );
      $(hotSpotDiv).addClass('panorama-custom-hotspot-selected');

    });
    $(hotSpotDiv).on('mouseout', function(){

      that.parentExplorer.displays.map.markerOut( );
      that.parentExplorer.displays.map.markerBounceEnd( args.id );

      that.parentExplorer.triggerEvent('resourceMouseOut', args );
      $(hotSpotDiv).removeClass('panorama-custom-hotspot-selected');
    });
  },




  centerInPanorama: function(id) {
    var that=this;

    var e = that.parentExplorer.resourceMinimalList.get(id);
    if( e.get('panoramaPitch') &&  e.get('panoramaYaw') ) {
      that.panorama.lookAt(  parseInt(e.get('panoramaPitch')), parseInt(e.get('panoramaYaw'))  )
      that.shakeHotspot(id);

      //cogumelo.log(e.get('panoramaPitch'), e.get('panoramaYaw') )

    }
  },


  shakeHotspot: function(id) {
    var that = this;
    var e = that.parentExplorer.resourceMinimalList.get(id);


    if( e.get('panoramaPitch') &&  e.get('panoramaYaw') ) {
      cogumelo.log(e.get('hotSpotDiv'));
      $(e.get('hotSpotDiv')).addClass('panorama-custom-hotspot-selected')
    }
  },


  unShakeHotsPot: function( id ) {
    var that = this;
    var e = that.parentExplorer.resourceMinimalList.get(id);


    if( e.get('panoramaPitch') &&  e.get('panoramaYaw') ) {
      cogumelo.log(e.get('hotSpotDiv'));
      $(e.get('hotSpotDiv')).removeClass('panorama-custom-hotspot-selected')
    }
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
