var geozzy = geozzy || {};


geozzy.explorerComponents.clusterRoseView = function( opts ) {

  var that = this;
  that.iconos = [];

  //  Options
  that.options = {
    map: false,
    circleColour: 'grey'
  }
  $.extend(true, that.options, opts);



  that.hide = function( ) {
    $( that.markerClustererHover ).hide();
  };

  that.show = function( markers ) {

    var top = ( $( that.options.map.getDiv() ).offset().top + p.y ) - that.markerClustererHover.height()/2;
    var left = ( $( that.options.map.getDiv() ).offset().left + p.x ) - that.markerClustererHover.width()/2;

    that.markerClustererHover.css("top", top+'px' );
    that.markerClustererHover.css("left", left+'px' );
    that.markerClustererHover.show();

    var elementsToPrint = 8;
    var angle = 7.175;
    var currentAngle = 0-angle;

    $( that.iconos ).each( function(i,e){
      e.remove();
    });

    that.iconos = [];

    $( markers ).each( function(i,e){

      currentAngle += angle;

      var icono = $('<img data-resource-id="'+e.explorerResourceId+'" src="'+e.getIcon().url+'">');
      icono.css('position', 'absolute');
      //icono.css('background', 'green');
      icono.css('width', e.getIcon().size.width + 'px');
      icono.css('height', e.getIcon().size.height + 'px');
      icono.css("border-radius", 300);
      //icono.css("border", '1px solid black');
      icono.css("zIndex", 8);


      var iconTopOrigin = that.markerClustererHover.height()/2 - icono.height()/2;
      var iconLeftOrigin = that.markerClustererHover.width()/2 - icono.width()/2;

      var iconTopDest = iconTopOrigin + ( 33 * Math.sin( currentAngle ));
      var iconLeftDest = iconLeftOrigin + ( 33 * Math.cos( currentAngle ));


      icono.css("top", (iconTopOrigin)  +'px' );
      icono.css("left", (iconLeftOrigin) + 'px' );

      icono.animate({
        top: (iconTopDest)  +'px',
        left: (iconLeftDest) + 'px'
      },
      {
        queue: false,
        duration: 123,
        complete: function() {
          icono.css("zIndex", 10);
        }
      });

      that.iconos.push(icono);

      icono.hover( function(iconoRoseta){
        that.markerHover( $(iconoRoseta.target).attr('data-resource-id') );
        icono.css('margin', '1px');
        icono.css( 'cursor', 'pointer' );
      });

      icono.bind('mouseleave', function(iconoRoseta){
        that.markerOut( $(iconoRoseta.target).attr('data-resource-id') );

        icono.css('margin', '0px');
        icono.css( 'cursor', 'arrow' );
      });

      elementsToPrint--;
      if(elementsToPrint <= 0) {
        return false;
      }

      that.markerClustererHover.append( icono );
    });

  }















  var overlay = new google.maps.OverlayView();
  overlay.draw = function() {};
  overlay.setMap(that.options.map);
  var proj = overlay.getProjection();
  var pos = markers[0].getPosition();
  var p = proj.fromLatLngToContainerPixel(pos);



  var insideDiv = $('<div></div>');
  that.markerClustererHover = $("<div></div>");
  that.markerClustererHover.css('position', 'absolute');
  //that.markerClustererHover.css('background', 'blue');

  insideDiv.css('background', that.options.circleColour);
  insideDiv.css('position', 'relative');
  insideDiv.css('width', '40px');
  insideDiv.css('height', '40px');
  insideDiv.css('margin', '28px');
  insideDiv.css("border-radius", insideDiv.width()/2);
  insideDiv.css("zIndex", 9);


  insideDiv.show();
  that.markerClustererHover.append( insideDiv );
  that.markerClustererHover.hide();


  $('body').append(that.markerClustererHover);



  that.markerClustererHover.css("border-radius",  that.markerClustererHover.width()/2);


  // hide event!
  $( that.markerClustererHover ).bind('mouseleave', function() {
    that.hide();
  });

}
