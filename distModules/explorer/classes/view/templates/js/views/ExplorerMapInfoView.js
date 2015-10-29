var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapInfoView = Backbone.View.extend({

  displayType: 'mapInfo',
  parentExplorer: false,
  template: _.template(""),
  containerMap: false,
  divId: 'geozzyExplorerMapInfo',

  initialize: function( opts ) {
    var that = this;
    that.options = new Object({

    });
    $.extend(true, that.options, opts);
  },

  createInfoMapDiv: function () {
    var that = this;
    var pos = that.getTopLeftPosition();

    var highest = -999;

    $("*").each(function() {
        var current = parseInt($(this).css("z-index"), 10);
        if(current && highest < current) highest = current+1;
    });

    if( $( '#'+that.divId ).length === 0 ) {
      $('body').append( '<div id="' + that.divId + '" ></div>' )
    }
    else {
      $( '#'+that.divId ).show();
    }

    $('#'+that.divId).css('position', 'absolute');
    $('#'+that.divId).css('background-color', 'red');

    $('#'+that.divId).css('width', 200+'px');
    $('#'+that.divId).css('height', 300+'px');

    $('#'+that.divId).css('top', pos.y+'px');
    $('#'+that.divId).css('left', pos.x+'px');
    $('#'+that.divId).css('z-index',highest);


  },

  render: function() {
    var that = this;
    that.createInfoMapDiv();
  },

  show: function( ) {
    var that = this;
    that.render( );
  },

  hide: function() {
    var that = this;

    $('#'+that.divId).hide();
  },

  getTopLeftPosition: function() {
    var that = this;


    return {x: $(that.parentExplorer.displays.map.map.getDiv() ).offset().left , y: $(that.parentExplorer.displays.map.map.getDiv() ).offset().top };
  }

});
