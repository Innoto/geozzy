var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapInfoView = Backbone.View.extend({

  displayType: 'mapInfo',
  parentExplorer: false,
  template: _.template(""),
  containerMap: false,
  divId: 'geozzyExplorerMapInfo',

  template: _.template('<div> title </div><div><img class="img-responsive" src="http://lorempixel.com/260/196/nature?putarrrl" /></div> '),

  margin: 10,

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
      $('body').append( '<div id="' + that.divId + '" >asdf</div>' )
    }


    $('#'+that.divId).css('position', 'absolute');

    $('#'+that.divId).css('background-color', 'white');
    $('#'+that.divId).css('top', pos.y+that.margin+'px');
    $('#'+that.divId).css('left', pos.x+that.margin+'px');
    $('#'+that.divId).css('z-index',highest);


  },

  render: function() {
    var that = this;
    that.createInfoMapDiv();

    var resourceInfo =

    $( '#'+that.divId ).html( that.template({title:'BLOBLO'}) )

  },

  show: function( id ) {
    var that = this;
    that.render( id );
    $( '#'+that.divId ).show();
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
