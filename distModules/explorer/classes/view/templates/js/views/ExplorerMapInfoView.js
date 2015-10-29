var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapInfoView = Backbone.View.extend({

  displayType: 'mapInfo',
  parentExplorer: false,
  template: _.template(""),
  containerMap: false,
  divId: 'geozzyExplorerMapInfo',

  initialize: function( opts ) {

    this.options = new Object({

    });
    $.extend(true, this.options, opts);
  },

  render: function() {

  },

  show: function( ) {

    this.render( );
  },

  hide: function() {
    console.log("HIDE");
  }



});
