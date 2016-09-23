
var geozzy = geozzy || {};

geozzy.storyComponents.routerInstance = false;
geozzy.story = function( opts ) {
  var that = this;



  that.options = {
     storySectionName: 'Geozzy  story',
     storyAPIHost: '/api/story/',
     storyId: false,
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
  }

  /*
  *   exec
  *   Fetch collections, initialize routers,and trigert first load events
  */
  that.exec = function() {
    if( !Backbone.History.started ){
      Backbone.history.start();
    }
    else {
      Backbone.history.stop();
      Backbone.history.start();
    }

    lang = that.getLang();
    that.storySteps.url = lang + that.options.storyAPIHost + 'story/' + storyId  ;

    that.storySteps.fetch({
      type: 'POST',
      data: that.options.aditionalParameters,
      success: function() {

      }
    });

  };



  that.getLang= function(){
    var lang = false;
    if (typeof(cogumelo.publicConf.C_LANG)!='undefined'){
      lang = '/'+cogumelo.publicConf.C_LANG;
    }
    return lang;
  }

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
      console.log('Geozzy story ERROR: Display type key not found');
    }
  }
}
