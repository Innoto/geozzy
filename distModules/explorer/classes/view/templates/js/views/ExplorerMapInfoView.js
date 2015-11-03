var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapInfoView = Backbone.View.extend({

  displayType: 'mapInfo',
  parentExplorer: false,
  template: _.template(""),
  containerMap: false,
  divId: 'geozzyExplorerMapInfo',

  template: _.template(
    '<div class="gempiImg"><img class="img-responsive" src="http://lorempixel.com/260/196/nature?prrrl" /></div>'+
    '<div class="gempiTitle"><%-title%></div>'+
    '<div class="gempiDescription">Sed vitae enim ex. Nullam urna eros, commodo in sodales sed, fermentum at quam. Nunc non ultrices mi. Nullam vel porttitor magna. Morbi fringilla purus ac pulvinar lacinia. Curabitur sollicitudin ultricies sodales. Nullam eu enim scelerisque, cursus lectus a, placerat elit. Interdum et malesuada fames ac ante ipsum primis in faucibus. Proin ut mi cursus, auctor magna et, porta tortor.</div>'
  ),

  marginX: 60,
  marginY: 20,

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

    $('#'+that.divId).css('top', pos.y+that.marginY+'px');
    $('#'+that.divId).css('left', pos.x+that.marginX+'px');
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
