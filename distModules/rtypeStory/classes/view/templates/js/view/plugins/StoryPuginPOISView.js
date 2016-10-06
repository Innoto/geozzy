var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};


geozzy.storyComponents.StoryPluginPOISView = Backbone.View.extend({
  displayType: 'plugin',
  parentStory: false,
  tplElement: false,

  poisExplorers = [],

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      container: false,
      tplElement: '<%-id%>'
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

    if( typeof that.poisExplorers[ step.id ] === 'undefined' )  {

      var ex = that.getExplorer();
      var ds = that.getExplorerMap();
      var popup = that.getExplorerInfoWindow();

      ex.addDisplay( ds );
      ex.addDisplay( popup );
      ex.exec();

      that.poisExplorers[ step.id ] = {
        ex: explorer ,
        ds: display,
        popup: popup
      };
    }
  },


  getExplorer: function() {
    var that = this;

    return explorer = new geozzy.explorer({
      explorerId:'pois',
      explorerSectionName: __('Points of interest'),
      debug:false,
      aditionalParameters: {resourceID: step.id},
      resetLocalStorage: true
    });
  },

  getExplorerMap: function {
    var that = this;

    return new geozzy.explorerComponents.mapView({

      map: geozzy.rExtMapInstance.resourceMap,
      clusterize:false/*,
      chooseMarkerIcon: function( markerData ) {
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

        poisTypes.each( function(e){
            console.log(cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'/resourcePoisCollection/marker.png')
            if( $.inArray(e.get('id'), markerData.get('terms')) > -1 ) {
              if( jQuery.isNumeric( e.get('icon') )  ){
                retMarker.url = cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'/resourcePoisCollection/marker.png';
                retMarker.size =  new google.maps.Size(20, 20);
                return false;
              }
            }
        });

      }*/
    }
  },

  getExplorerInfoWindow: function() {
    var miniInfoWindow = ''+
      '<div class="poiInfoWindow">'+
        '<div class="poiImg">'+
          '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-image%>/squareCut/<%-image%>.jpg" />'+
        '</div>'+
        '<div class="poiInfo">'+
          '<div class="poiTitle"><p><%-title%></p></div>'+
          '<div class="poiDescription"><%-description%></div>'+
          '<% if( isNormalResource == 1 ) { %> <a target="_blank" href="/resource/<%-id%>" ><button class="btn btn-primary accessButton">' + __('Discover') + '</button> </a><% }%>'

        '</div>'
      '</div>';


    return new geozzy.explorerComponents.mapInfoBubbleView({
      tpl:miniInfoWindow,
      width: 350,
      max_height:170
    });
  }

});
