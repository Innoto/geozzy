var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};


geozzy.storyComponents.StoryPluginPOISView = Backbone.View.extend({
  displayType: 'plugin',
  parentStory: false,
  tplElement: false,
  currentPOISExplorer: false,
  poisExplorers: [],
  poisTypes:false,

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      container: false,
      tplElement: geozzy.storyComponents.InfowindowPOI
    });
    that.options = $.extend(true, {}, options, opts);


    that.tplElement = _.template(that.options.tplElement);
  },

  setParentStory: function( obj ) {
    var that = this;
    that.parentStory = obj;
  },

  render: function() {
    var that = this;

    that.parentStory.bindEvent('stepChange', function(obj){
      that.setStep(obj);
    });

  },

  setStep: function( step ) {
    var that = this;

    that.loadPoisTypes( function() {
      // hide other explorer POIS
      that.hideAllPOIS();

      if( typeof that.poisExplorers[ step.id ] === 'undefined' )  {

        var ex = that.getExplorer( step.id );
        var ds = that.getExplorerMap( step.id );
        var popup = that.getExplorerInfoWindow( step.id );

        ex.addDisplay( ds );
        ex.addDisplay( popup );
        ex.exec();

        that.poisExplorers[ step.id ] = {
          explorer: ex ,
          display: ds,
          popup: popup
        };
      }

      that.poisExplorers[ step.id ].display.showAllMarkers();
    });
  },


  getExplorer: function( stepId ) {
    var that = this;

    return explorer = new geozzy.explorer({
      explorerId:'pois',
      explorerSectionName: __('Points of interest'),
      debug:false,
      aditionalParameters: {resourceID: stepId },
      resetLocalStorage: true,
      useUrlRouter: false
    });
  },

  getExplorerMap: function() {
    var that = this;

    if( typeof that.parentStory.displays.background.options.map == 'undefined' ) {
      cogumelo.log('STORY ERROR: Story background map not defined')
    }

    return new geozzy.explorerComponents.mapView({
      map: that.parentStory.displays.background.options.map,
      chooseMarkerIcon: function( markerData ) {
        return that.chooseMarker( markerData );

      }
    });
  },

  chooseMarker: function( markerData) {
    var that = this;

    //return cogumelo.publicConf.media+'/module/rextPoiCollection/img/poi.png';

    var retMarker = {
      url: cogumelo.publicConf.media+'/module/rextPoiCollection/img/chapaPOIS.png',
      // This marker is 20 pixels wide by 36 pixels high.
      size: new google.maps.Size(20, 20),
      // The origin for this image is (0, 0).
      origin: new google.maps.Point(0, 0),
      // The anchor for this image is the base of the flagpole at (0, 36).
      anchor: new google.maps.Point(10, 10)
    };

    that.poisTypes.each( function(e){
        cogumelo.log(cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'/resourcePoisCollection/marker.png')
        if( $.inArray(e.get('id'), markerData.get('terms')) > -1 ) {
          if( jQuery.isNumeric( e.get('icon') )  ){
            retMarker.url = cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'/resourcePoisCollection/marker.png';
            retMarker.size =  new google.maps.Size(20, 20);
            return false; // FOREACH RETURN
          }
        }
    });

    return retMarker;

  },

  getExplorerInfoWindow: function() {
    var that = this

    return new geozzy.explorerComponents.mapInfoBubbleView({
      tpl: that.options.tplElement,
      width: 350,
      max_height:170,
      map_scrollwhell_is_enabled: false
    });
  },

  hideAllPOIS : function() {
    var that = this;

    $.each( that.poisExplorers, function(i, explorer) {
      if( typeof explorer != 'undefined') {
        explorer.display.hideAllMarkers();
      }

    });
  },


  loadPoisTypes: function( readyCallback ) {
    var that = this;

    if( that.poisTypes == false ) {
      var poisTypes = new geozzy.collection.CategorytermCollection();
      poisTypes.setUrlByIdName('rextPoiType');

      $.when( poisTypes.fetch() ).done(function() {
        that.poisTypes = poisTypes;
        readyCallback();
      });
    }
    else {
      readyCallback();
    }
  }

});
