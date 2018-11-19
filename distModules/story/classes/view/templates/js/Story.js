
var geozzy = geozzy || {};

geozzy.storyComponents.routerInstance = false;
geozzy.story = function( opts ) {
  var that = this;



  that.options = {
     storySectionName: 'Geozzy  story',
     storyAPIHost: '/api/storySteps/',
     storyReference: false,
     aditionalParameters: {},
  };


  $.extend(true, that.options, opts);

  // step collection
  that.storySteps = new geozzy.storyComponents.StoryStepCollection();
  // story events
  that.storyEvents = [];
  // displays
  that.displays = {
    background: false,
    list: false,
    plugins: []
  };

  /*
  *   exec
  *   Fetch collections, initialize routers,and trigert first load events
  */
  that.exec = function() {

    // set explorer router
    geozzy.storyComponents.routerInstance = new geozzy.storyComponents.mainRouter();
    geozzy.storyComponents.routerInstance.parentStory = that;
    geozzy.storyComponents.routerInstance.route( window.location.pathname.substring(1), 'main' );
/*
    if( !Backbone.History.started ){
      Backbone.history.start({ pushState: true });
    }
    else {
      Backbone.history.stop();
      Backbone.history.start({ pushState: true });
    }*/

    lang = that.getLang();
    that.storySteps.url = lang + that.options.storyAPIHost + 'resource/' + that.options.storyReference  ;

    that.storySteps.fetch({
      //type: 'POST',
      data: that.options.aditionalParameters,
      success: function() {
        that.render();
      }
    });

  };

  that.render = function() {
    var that = this;

    if( that.displays.background ) {
      that.displays.background.render();
    }

    if( that.displays.list ) {
      that.displays.list.render();
    }

    $(that.displays.plugins).each( function(i,e){
      e.render();
    });

    that.triggerEvent('storyReady');

  };

  that.getLang= function(){
    var lang = false;

    if (typeof(cogumelo.publicConf.C_LANG)!='undefined'){
      lang = '/'+cogumelo.publicConf.C_LANG;
    }
    return lang;
  };

  that.triggerEvent = function( eventName, parameters) {
    var that = this;


    $.each( that.storyEvents, function( i, event ){
      if( typeof event.name != 'undefined' && event.name == eventName  ) {
        if( typeof event.action != 'undefined'  ) {
          event.action( parameters );
        }
      }
    });
  };


  that.bindEvent = function( eventName, action ) {
    var that = this;

    that.storyEvents.push( {
      name: eventName,
      action: action
    });
  };

  that.addDisplay = function( displayObj ){

    if( displayObj.displayType == 'background' ) {
      that.displays.background = displayObj;
      that.displays.background.setParentStory( that );
    }
    else
    if( displayObj.displayType == 'list' ) {
      that.displays.list = displayObj;
      that.displays.list.setParentStory( that );
    }
    else
    if( displayObj.displayType == 'plugin' ) {
      displayObj.setParentStory( that );
      that.displays.plugins.push( displayObj );
    }
    else {
      cogumelo.log('Geozzy story ERROR: Display type key not found');
    }

  };
};
