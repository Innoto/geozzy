var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.infoView = Backbone.View.extend({

  initialize: function( opts ) {

    this.options = new Object({

    });
    $.extend(true, this.options, opts);

    this.setMap( this.options.map );
  },

  setData: function() {

  },

  render: function() {

  },

  hide: function() {

  }




});
