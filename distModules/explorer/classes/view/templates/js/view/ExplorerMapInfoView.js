var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapInfoView = Backbone.View.extend({

  displayType: 'mapInfo',
  parentExplorer: false,
  template: _.template(""),
  containerMap: false,
  divId: 'geozzyExplorerMapInfo',

  currentMousePos: { x: -1, y: -1 },




  template: _.template(
    '<div class="gempiContent">'+
      '<div class="gempiImg">'+
        '<img class="img-responsive" src="/cgmlImg/<%-img%>/fast_cut/.jpg" />'+
        '<div class="gempiFav"><% if(touchAccess){ %><i class="fa fa-heart-o"></i><i class="fa fa-heart"></i> <% } %></div>'+
      '</div>'+
      '<div class="gempiInfo">'+
        '<div class="gempiTitle"><%-title%></div>'+
        '<div class="gempiLocation"><% if(city){ %><%- city %> <% } %></div>'+
        '<div class="gempiDescription"><%-description%></div>'+
        '<div class="gempiTouchAccess"><% if(touchAccess){ %><button class="btn btn-primary accessButton">Descúbreo</button> <% } %></div>'+
      '</div>'+
    '</div>'
  ),

  marginX: 25,
  marginY: 20,

  ready: true,

  initialize: function( opts ) {
    var that = this;
    var options = new Object({

    });

    that.options = $.extend(true, {}, options, opts);


    that.mousePosEventListener();

  },


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

  render: function( id ) {
    var that = this;
    that.createInfoMapDiv();

    var resourceInfo = new Backbone.Model(  );

    resourceInfo.set(that.parentExplorer.resourceMinimalList.get(id).toJSON());


    that.ready = id;

    that.parentExplorer.fetchPartialList(
       [id],
       function() {


         var element = {
           id: that.parentExplorer.resourcePartialList.get( id ).get('id'),
           inMap: that.parentExplorer.resourceMinimalList.get( id ).get('mapVisible'),
           img: that.parentExplorer.resourceMinimalList.get( id ).get('img'),
           title: that.parentExplorer.resourcePartialList.get( id ).get('title'),
           description: that.parentExplorer.resourcePartialList.get( id ).get('description'),
           city: that.parentExplorer.resourcePartialList.get( id ).get('city'),
           touchAccess: that.parentExplorer.explorerTouchDevice
         };


         $( '#'+that.divId ).html( that.template( element ) );

         if( that.ready == id){
          $( '#'+that.divId ).show();
          that.moveInfoMapDivWhenBehindMouse();
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
