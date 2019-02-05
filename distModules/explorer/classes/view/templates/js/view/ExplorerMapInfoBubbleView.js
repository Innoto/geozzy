var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.mapInfoBubbleView = Backbone.View.extend({

  displayType: 'plugin',
  parentExplorer: false,
  template: _.template(""),
  //divId: 'geozzyExplorerMapInfo',
  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      boxId: 'explorerInfoBubble',
      tpl: geozzy.explorerComponents.mapInfoViewTemplate,
      marker_distance: [10,8], // [top, bottom]
      max_height: 240,
      width: 255,
      map_scrollwhell_is_enabled: true
    });

    that.options = $.extend(true, {}, options, opts);

    that.template = _.template( that.options.tpl );




  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('resourceHover', function( params ){

        that.show( params );

    });

    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      that.hide( params );
    });

    that.parentExplorer.bindEvent('resourceClick', function( params ){

      /*if(that.parentExplorer.explorerTouchDevice) {
        that.show( params );
      }*/

    });

  },



  show: function( params ) {
    var that = this;
    var m = that.parentExplorer.resourceMinimalList.get( params.id ).get('mapMarker');

    if( typeof that.infowindow == 'undefined') {
      that.infowindow = new smart_infowindow(
        {
          map: that.parentExplorer.displays.map.map,
          marker_distance: that.options.marker_distance, // [top, bottom]
          max_height: that.options.max_height,
          width: that.options.width,
          keep_open: true,
          map_scrollwhell_is_enabled: that.options.map_scrollwhell_is_enabled ,
          peak_img: '/vendor/yarn/tiny_map_utilities/smart_infowindow/img/peak.png',
          onAddSuccess: function() {
            that.infowindow.open(m, 'mouseover' , that.renderContent(params.id), true);
          }
        }
      );

    }
    else {
      that.infowindow.open(m, 'mouseover' ,that.renderContent(params.id), true);
    }


  },

  hide: function( params) {
    var that = this;

    setTimeout(
      function() {
        //smart_infowindow_click_event_opened = false;
        if(smart_infowindow_is_on_infowindow == false) {
          that.infowindow.close();
        }
      },
    10 );

  },

  renderContent: function( id ) {
    var that = this;

    var retHtml = '';
    var resourceInfo = new Backbone.Model(  );

    resourceInfo.set(that.parentExplorer.resourceMinimalList.get(id).toJSON());

    that.ready = id;

    that.parentExplorer.fetchPartialList(
      [id],
      function() {

         var minJSON = that.parentExplorer.resourceMinimalList.get( id ).toJSON();
         var partJSON = that.parentExplorer.resourcePartialList.get( id ).toJSON();

         var element = $.extend( true, partJSON, minJSON );

         element.touchAccess = that.parentExplorer.explorerTouchDevice;
         retHtml = that.template( element );
      }
    );

    return retHtml;
  }

  /*,


  mousePosEventListener: function() {
    var that = this;

    $(document).mousemove(function(event) {
        that.currentMousePos.x = event.pageX;
        that.currentMousePos.y = event.pageY;
    });
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

  moveInfoMapDivWhenBehindMouse: function() {
    var that = this;

    var infoDiv = $( '#'+that.divId );




    if(
       infoDiv.offset().left < that.currentMousePos.x &&  infoDiv.offset().left + infoDiv.width() > that.currentMousePos.x &&
       infoDiv.offset().top < that.currentMousePos.y &&  infoDiv.offset().top + infoDiv.height() > that.currentMousePos.y
    ) {
      var pos = that.getTopLeftPosition();
      $('#'+that.divId).css('left', ( that.currentMousePos.x + 20 ) +'px');
    }
  },

  render: function( ) {
    var that = this;


  },

  show: function( id ) {
    var that = this;

    that.createInfoMapDiv();

    var resourceInfo = new Backbone.Model(  );

    resourceInfo.set(that.parentExplorer.resourceMinimalList.get(id).toJSON());


    that.ready = id;

    that.parentExplorer.fetchPartialList(
      [id],
      function() {

         var minJSON = that.parentExplorer.resourceMinimalList.get( id ).toJSON();
         var partJSON = that.parentExplorer.resourcePartialList.get( id ).toJSON();

         var element = $.extend( true, partJSON, minJSON );

         element.touchAccess = that.parentExplorer.explorerTouchDevice;

         $( '#'+that.divId ).html( that.template( element ) );

         if( that.ready == id){
          $( '#'+that.divId ).show();
          that.moveInfoMapDivWhenBehindMouse();
         }
      }
    );
  },

  hide: function() {
    var that = this;


    that.ready = false;
    $('#'+that.divId).hide();
    $('#'+that.divId+ ' *').remove();
  },

  getTopLeftPosition: function() {
    var that = this;


    return {x: $(that.parentExplorer.displays.map.map.getDiv() ).offset().left , y: $(that.parentExplorer.displays.map.map.getDiv() ).offset().top };
  }
*/
});
