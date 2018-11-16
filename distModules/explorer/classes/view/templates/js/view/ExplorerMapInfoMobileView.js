var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

var mapInfoMobileView_is_blocked = false;

geozzy.explorerComponents.mapInfoMobileView = Backbone.View.extend({

  displayType: 'plugin',
  parentExplorer: false,
  template: _.template(""),
  containerMap: false,
  divId: 'geozzyExplorerMapInfoMobile',
  currentMousePos: { x: -1, y: -1 },
  ready: true,

  events: {
    // resource events
    "click .accessButton": "resourceClick",
    "click .nextButton": "next",
    "click .previousButton": "previous",
    "click .closeButton": "close",
    "swipeleft": "next",
    "swiperight": "previous"
  },


  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      tpl: geozzy.explorerComponents.mapInfoViewMobileTemplate,
    });

    that.options = $.extend(true, {}, options, opts);

    that.template = _.template( that.options.tpl );
    that.mousePosEventListener();
  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      that.hide(params.id);
    });

    that.parentExplorer.bindEvent('resourceHover', function( params ){
      that.show(params.id);
    });

    that.parentExplorer.bindEvent('mobileTouch', function( params ){
      that.show(params.id);
    });




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
      $('body').append( '<div id="' + that.divId + '" style="" ></div>' );
    }



    //$('#'+that.divId).css('z-index',highest);
  },


  renderDialog: function( id ) {


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
           that.$el = $('#'+that.divId);
           that.delegateEvents();
           that.updateArrows();
          $( '#'+that.divId ).show();
         }

      }
    );

  },

  close: function() {
    var that = this;
    that.parentExplorer.triggerEvent('mapInfoWindowMobileClose', false);
    that.hide();
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
  },

  resourceClick: function() {
    var that = this;

    that.parentExplorer.triggerEvent('resourceClick',{
      id: that.ready,
      section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
    });
  },
  next: function() {
    var that = this;
    var id = that.ready;
    var collectionList = that.parentExplorer.resourceMinimalList;

    var index =  collectionList.indexOf( collectionList.get( id ) );
    if ((index + 1) !== collectionList.length) {
      var resId = collectionList.at(index + 1).get('id');
      that.parentExplorer.triggerEvent('mobileTouch', {id:resId});
      that.parentExplorer.triggerEvent('resourceHover', {id:resId});
      $( '#'+that.divId+' .gempiItem').animate({
          right: '800px'
      }, 250);
      setTimeout(function(){
        that.show( resId );
      }, 250);
    }
  },

  previous: function() {
    var that = this;
    var id = that.ready;
    var collectionList = that.parentExplorer.resourceMinimalList;

    var index =  collectionList.indexOf( collectionList.get( id ) );
    if ((index -1 ) >= 0) {
      var resId = collectionList.at(index - 1).get('id');
      that.parentExplorer.triggerEvent('mobileTouch', {id:resId});
      that.parentExplorer.triggerEvent('resourceHover', {id:resId});
      $( '#'+that.divId+' .gempiItem').animate({
          left: '800px'
      }, 250);
      setTimeout(function(){
        that.show( resId );
      }, 250);
    }

  },

  updateArrows: function() {
    var that = this;
    var id = that.ready;
    var collectionList = that.parentExplorer.resourceMinimalList;
    var index =  collectionList.indexOf( collectionList.get( id ) );

    if ( index == 0) {
      that.$el.find('.previousButton').hide();
    }
    else {
      that.$el.find('.previousButton').show();
    }

    if ( index  === collectionList.length-1) {
      that.$el.find('.nextButton').hide();
    }
    else {
      that.$el.find('.nextButton').show();
    }
  }




});
