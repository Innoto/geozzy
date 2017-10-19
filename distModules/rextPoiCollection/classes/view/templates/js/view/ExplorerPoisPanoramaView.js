var geozzy = geozzy || {};
geozzy.explorerComponents = geozzy.explorerComponents || {}; //if(!geozzy.explorerComponents) geozzy.explorerComponents={};


geozzy.explorerComponents.panoramaView = Backbone.View.extend({

  tpl: false,
  tplElement: false,

  displayType: 'plugin',
  parentExplorer: false,
  visibleResources: [],

  events: {
  },

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      containerDiv: $('.panoramaView'),
      panoramaImage: '/pannellum/panorama.jpg'
    });

    that.options = $.extend(true, {}, options, opts);
  },


  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
  },

  render: function() {
    var that = this;

    $.each(  this.visibleResources, function(i,e){

    });
    that.parentExplorer.triggerEvent('onRenderListComplete',{});
  },


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

});
