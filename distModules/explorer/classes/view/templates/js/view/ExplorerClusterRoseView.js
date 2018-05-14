var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};


geozzy.explorerComponents.clusterRoseView = function( opts ) {

  var that = this;
  that.iconos = [];
  that.blocked = false;
  //  Options
  that.options = {
    mapView: false,
    circleColour: '#3A4747'
  }
  $.extend(true, that.options, opts);


  google.maps.event.addListener( that.options.mapView.map, 'bounds_changed', function() {
    //alert('')
    that.hide();
  });

  that.hide = function( ) {
    $( that.markerClustererHover ).hide();
  };

  that.show = function( markers ) {
    if(! that.blocked) {
      that.realShow(markers);
    }
  }

  that.realShow = function( markers ) {

    var proj = overlay.getProjection();
    var pos = markers[0].getPosition();
    var p = proj.fromLatLngToContainerPixel(pos);

    var top = ( $( that.options.mapView.map.getDiv() ).offset().top + p.y ) - that.markerClustererHover.height()/2;
    var left = ( $( that.options.mapView.map.getDiv() ).offset().left + p.x ) - that.markerClustererHover.width()/2;

    that.markerClustererHover.css("top", top+'px' );
    that.markerClustererHover.css("left", left+'px' );
    that.markerClustererHover.css("z-index", 11 );
    that.markerClustererHover.show();

    that.markerClustererHover.find(".markerClustererHoverCircle").unbind("click");
    that.markerClustererHover.find(".markerClustererHoverCircle").bind("click", function(){

      that.blocked = true;
      that.hide();
      that.options.mapView.map.setCenter(pos);
      that.options.mapView.map.setZoom( that.options.mapView.map.getZoom() + 1 );

      setTimeout( function(){
        that.blocked= false;
      }, 100);

    });


    var elementsToPrint = 8;
    var angle = 7.175;
    var currentAngle = 0-angle;

    $( that.iconos ).each( function(i,e){
      e.remove();
    });

    that.iconos = [];


    if ( markers.length <= 7 ) {
      $( markers ).each( function(i,e){

        currentAngle += angle;

        var icono = $('<img data-resource-id="'+e.explorerResourceId+'" src="'+e.getIcon().url+'">');
        icono.css('position', 'absolute');
        //icono.css('background', 'green');
        icono.css('width', e.getIcon().size.width + 'px');
        icono.css('height', e.getIcon().size.height + 'px');
        icono.css("border-radius", 300);

        //icono.css("border", '1px solid black');
        icono.css("zIndex", 10);


        var iconTopOrigin = that.markerClustererHover.height()/2 - icono.height()/2;
        var iconLeftOrigin = that.markerClustererHover.width()/2 - icono.width()/2;

        var iconTopDest = iconTopOrigin + ( 29 * Math.sin( currentAngle ));
        var iconLeftDest = iconLeftOrigin + ( 29 * Math.cos( currentAngle ));


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
            icono.css("zIndex", 12);
          }
        });

        that.iconos.push(icono);


        icono.bind('click', function(iconoRoseta){
          that.hide();
          that.options.mapView.markerClick( $(iconoRoseta.target).attr('data-resource-id') );
          //that.options.mapView.parentExplorer.triggerEvent('resourceClick', {id: $(iconoRoseta.target).attr('data-resource-id') });
          //that.options.mapView.parentExplorer.triggerEvent('resourceMouseOut', {id: $(iconoRoseta.target).attr('data-resource-id') });
        });

        icono.bind('mouseenter', function(iconoRoseta){
          //that.options.mapView.markerHover( $(iconoRoseta.target).attr('data-resource-id') );

          that.options.mapView.parentExplorer.triggerEvent('resourceHover', {id: $(iconoRoseta.target).attr('data-resource-id') });
          icono.css('margin', '1px');
          icono.css( 'cursor', 'pointer' );
        });

        icono.bind('mouseleave', function(iconoRoseta){
        //  that.options.mapView.markerOut( $(iconoRoseta.target).attr('data-resource-id') );

          that.options.mapView.parentExplorer.triggerEvent('resourceMouseOut', {id: $(iconoRoseta.target).attr('data-resource-id') });

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
  }





  var overlay = new google.maps.OverlayView();
  overlay.draw = function() {};
  overlay.setMap(that.options.mapView.map);

  var insideDiv = $("<div class='markerClustererHoverCircle'><i class='fa fa-search'></i></div>");
  that.markerClustererHover = $("<div></div>");
  that.markerClustererHover.css('position', 'absolute');
  //that.markerClustererHover.css('background', 'blue');

  insideDiv.css('background', that.options.circleColour);
  insideDiv.css('position', 'relative');
  insideDiv.css('width', '33px');
  insideDiv.css('height', '33px');
  insideDiv.css('margin', '28px');
  insideDiv.css('border', '2px solid #fff');
  insideDiv.css("border-radius", (insideDiv.width()+4)/2);
  insideDiv.css("zIndex", 11);

  //insideDiv.find("i").hide();
  insideDiv.find("i").css('margin-left', '7px');
  insideDiv.find("i").css('margin-top', '7px');
  insideDiv.find("i").css('position', 'absolute');  
  insideDiv.find("i").css('color', '#fff');
  insideDiv.css( 'cursor', 'pointer' );
/*  insideDiv.find("i").css('margin', 'auto');
  insideDiv.find("i").css('background-color', 'red');
*/
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
