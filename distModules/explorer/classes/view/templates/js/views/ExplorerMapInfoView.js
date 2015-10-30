var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapInfoView = Backbone.View.extend({

  displayType: 'mapInfo',
  parentExplorer: false,
  template: _.template(""),
  containerMap: false,
  divId: 'geozzyExplorerMapInfo',

  template: _.template('<div> <%-title%> </div><div><img class="img-responsive" src="http://lorempixel.com/260/196/nature?putarrrl" /></div> '),

  margin: 10,

  ready: true,

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


    $('#'+that.divId).css('position', 'absolute');

    $('#'+that.divId).css('background-color', 'white');
    $('#'+that.divId).css('top', pos.y+that.margin+'px');
    $('#'+that.divId).css('left', pos.x+that.margin+'px');
    $('#'+that.divId).css('z-index',highest);


  },

  render: function( id ) {
    var that = this;
    that.createInfoMapDiv();

    var resourceInfo = new Backbone.Model(  );

    resourceInfo.set(that.parentExplorer.resourceMinimalList.get(id).toJSON());


    that.ready = id;

    that.parentExplorer.fetchPartialList(
       [id],
       function() {

         $( '#'+that.divId ).html( that.template(  that.parentExplorer.resourcePartialList.get(id).toJSON() ) );

         if( that.ready == id){
          $( '#'+that.divId ).show();
        }
       }
    );

  },

  show: function( id ) {
    var that = this;
    that.render( id );
  },

  hide: function() {
    var that = this;


    that.ready = false;
    $('#'+that.divId).hide();
  },

  getTopLeftPosition: function() {
    var that = this;


    return {x: $(that.parentExplorer.displays.map.map.getDiv() ).offset().left , y: $(that.parentExplorer.displays.map.map.getDiv() ).offset().top };
  }

});
