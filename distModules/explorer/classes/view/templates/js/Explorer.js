var geozzy = geozzy || {};

//geozzy.explorerComponents.routerInstance = false;
geozzy.explorer = function( opts ) {

  var that = this;


  that.getLang= function(){
    var lang = false;
    if (typeof(cogumelo.publicConf.C_LANG)!='undefined'){
      lang = '/'+cogumelo.publicConf.C_LANG;
    }
    return lang;
  };

  //  Options

  that.options = {
    explorerSectionName: 'Geozzy Explorer',
    explorerAPIHost: '/api/explorer/',
    explorerId: 'default',
    aditionalParameters: {},
    resetLocalStorage:false,

    // cache times (in seconds)
    cacheTimeIndex: 20,
    debug: false,
    useUrlRouter: false
  };

  $.extend(true, that.options, opts);

  that.explorerTouchDevice = $('html').hasClass('touch');

  // events
  that.explorerEvents = [];

  //  Debuger
  if( that.options.debug ) {
    that.timeDebugerMain = new TimeDebuger( {instanceName:'Explorer main'} );
    that.timeDebugerExtended =  new TimeDebuger( {instanceName:'Explorer extended data'} );
  }

  // Resource Collections and Indexes
  that.resourceIndex = false;
  that.resourceMinimalList = new geozzy.explorerComponents.resourceMinimalCollection();
  that.resourcePartialList =  false;//new geozzy.explorerComponents.resourcePartialCollection();


  // Displays
  that.displays = {
    map: false,
    activeList: false,
    reccomendList: false,
    plugins: []
  };

  // filters
  that.filters = [ ];




  if( that.options.useUrlRouter == true && typeof geozzy.explorerComponents.routerInstance == 'undefined' ) {
    geozzy.explorerComponents.routerInstance = new geozzy.explorerComponents.mainRouter();
    geozzy.explorerComponents.routerInstance.parentExplorer = that;
    geozzy.explorerComponents.routerInstance.route( window.location.pathname.substring(1), 'explorerMain' );
  }


  //
  // First Execution
  //

  that.exec = function( ) {
    // set multiple fetches
    lang = that.getLang();

    that.resourceMinimalList.url = lang + that.options.explorerAPIHost + 'explorer/' + that.options.explorerId+ '/request/minimal';

    that.bindEvent('resourceClick', function(param){

      if( that.options.useUrlRouter == true ){
        that.navigateUrl( param.id );
        that.triggerEvent('resourceAccess', {id: param.id});
        //that.parentExplorer.options.resourceAccess(id);
        if(that.explorerTouchDevice) {
          that.triggerEvent('resourceMouseOut', {id:0});
        }

      }
    });

    // render filters
    that.renderFilters();


    that.resourceMinimalList.fetch(
      {
        cache: true,
        type: 'POST',
        data: that.options.aditionalParameters ,
        expires: that.options.cacheTimeIndex ,
        success: function() {

          if( that.options.debug ) {
            that.timeDebugerMain.log('&nbsp;- Fetch first resource index with '+ that.resourceMinimalList.length + ' elements');
          }
          that.resourceIndex = new Backbone.Obscura(that.resourceMinimalList);
          if( that.options.debug ) {
            that.timeDebugerMain.log( '&nbsp;- Resources Indexed first time' );
          }
          that.applyFilters();
          //that.options.minimalLoadSuccess();
          that.triggerEvent('minimalLoadSuccess',{});
        }
      }

    );

  };


  //
  // Render filters
  //
  that.renderFilters = function() {
    if( that.filters.length > 0 ) {
      $.each( that.filters, function(i,e){
        //console.log('FILTRO',e)
        e.render();
      });
    }
  };



  //
  // Navigate url
  //
  that.navigateUrl = function( rid ) {
    if( that.options.useUrlRouter == true ) {
      if( Backbone.History.started ){
        Backbone.history.stop();
      }
      Backbone.history.start({ pushState: true }); // usar pushstate de forma temporal
      geozzy.explorerComponents.routerInstance.navigate( that.resourcePartialList.get(rid).getUrl() , false);
      Backbone.history.stop();
      Backbone.history.start();
    }
  };


  //
  // Add filter
  //
  that.addFilter = function( filter ) {
    filter.parentExplorer = this;
    that.filters.push( filter );
  };


  //
  // Apply filters
  //
  that.applyFilters = function() {

    if( that.options.debug ) {
      that.timeDebugerMain.reset();
      that.timeDebugerExtended.reset();
    }

    if(typeof that.resourceIndex.removePagination != 'undefined'){
      that.resourceIndex.removePagination();
    }

    // Set filters for current index
    if( typeof that.resourceIndex.filterBy != 'undefined') {
      that.resourceIndex.filterBy( function(model) {

        var matches = 0;
        var ret = false;

        $.each( that.filters, function(i, filter){
          if( filter.filterAction( model ) ) {
            matches++;
            return;
          }
        });

        if( matches == that.filters.length ) {
          ret = true;
        }

        // if matches number is same of filters number
        return ret;

      });

      if( that.options.debug ) {
        that.timeDebugerMain.log( '&nbsp;- Resultado filtrado final '+ that.resourceIndex.length + ' Records' );
      }



      that.render();
      that.triggerEvent('applyFilters', {});
    }

  };




  //
  // Display methods
  //
  that.addDisplay = function( displayObj ){

    if( displayObj.displayType == 'map' ) {
      that.displays.map = displayObj;
      that.displays.map.setParentExplorer( that );
    }
    else
    if( displayObj.displayType == 'activeList' ) {
      that.displays.activeList = displayObj;
      that.displays.activeList.setParentExplorer( that );
    }
    else
    if( displayObj.displayType == 'reccomendList' ) {
      that.displays.reccomendList = displayObj;
      that.displays.reccomendList.setParentExplorer( that );
    }
    else
    if( displayObj.displayType == 'plugin' ) {
      displayObj.setParentExplorer( that );
      that.displays.plugins.push( displayObj );
    }
    else {
      cogumelo.log('Geozzy explorer ERROR: Display type key not found');
    }
  };



  that.render = function( dontRenderMap ) {

    var resourcesToLoad = [];
    var metricData = {bounds:[], zoom: false,  filters:[0], explorerId: that.options.explorerId };


    if(that.displays.map ) {

      if( that.displays.map.isReady() ){

        var mapbounds = that.displays.map.getMapBounds();
        metricData.zoom = that.displays.map.map.getZoom();

        metricData.bounds = [ [mapbounds[0].lat(), mapbounds[0].lng()], [mapbounds[1].lat(), mapbounds[1].lng()] ];

        resourcesToLoad = that.displays.map.getVisibleResourceIds();
        //resourcesToLoad = $.merge( that.displays.map.getVisibleResourceIds() , resourcesToLoad);

        $.each(that.filters, function(i,e) {
          metricData.filters = $.merge( metricData.filters, e.getSelectedTerms() );

        });

        // context changed and send metrics by parameter
        that.triggerEvent('contextChange', metricData );
      }

      if( !dontRenderMap ) {
        that.displays.map.render();
      }
    }

    if(that.displays.activeList) {
      that.displays.activeList.currentPage = 0;
      resourcesToLoad = $.merge( that.displays.activeList.getVisibleResourceIds() , resourcesToLoad);
    }

    if(that.displays.pasiveList) {
      that.displays.pasiveList.currentPage = 0;
      resourcesToLoad = $.merge( that.displays.pasiveList.getVisibleResourceIds() , resourcesToLoad);
    }



    // Advanced Fetch
    if( that.options.debug ) {
      that.timeDebugerExtended.log('Starting second data fetch at');
    }

    var isFirstTime;
    if( that.resourcePartialList == false ) {
      isFirstTime = true;
      that.bindEvent('minimalLoadSuccess', function(){
        that.fetchPartialList(
          resourcesToLoad,
          function() {
            that.renderPartialList();
            that.triggerEvent('partialLoadSuccess', {});
          }
        );
      });

    }
    else {
      isFirstTime = false;
      that.fetchPartialList(
        resourcesToLoad,
        function() {
          that.renderPartialList();
          that.triggerEvent('partialLoadSuccess', {});
        }
      );
    }

  };


  that.fetchPartialList = function( resourcesToLoad, fetchSuccess ) {
    var lang = that.getLang();

    if( that.resourcePartialList === false ) {
      var partialCollection = geozzy.explorerComponents.resourcePartialCollection.extend(
        {
          url: lang + that.options.explorerAPIHost + 'explorer/' + that.options.explorerId+ '/request/partial'
        });

      that.resourcePartialList = new partialCollection();

      if( that.options.resetLocalStorage === true ) {
        that.resourcePartialList.reset();
        that.resourcePartialList.saveLocalStorage();
      }
    }

    that.resourcePartialList.fetchByIds({
      ids: resourcesToLoad,
      success: function() {
        fetchSuccess();

      }
    });
  };


  that.renderPartialList = function(){
    if( that.options.debug ) {
      that.timeDebugerExtended.log( '&nbsp;- Fetch partial resource data' );
    }

    if(that.displays.activeList) {
      that.displays.activeList.render();
    }

    if(that.displays.reccomendList) {
      that.displays.reccomendList.render();
    }

    if( that.displays.plugins.length > 0 ) {
      $.each( that.displays.plugins, function(pluginIndex, plugin) {
        plugin.render();
      });
    }
    if( that.options.debug ) {
      that.timeDebugerExtended.log( '&nbsp;- Render lists' );
    }
  };



  that.triggerEvent = function( eventName, parameters) {
    var that = this;

    $.each( that.explorerEvents, function( i, event ){
      if( typeof event.name != 'undefined' && event.name == eventName  ) {
        if( typeof event.action != 'undefined'  ) {
          event.action( parameters );
        }
      }
    });
  };


  that.bindEvent = function( eventName, action ) {
    var that = this;
    that.explorerEvents.push( {
      name: eventName,
      action: action
    });
  };

};
