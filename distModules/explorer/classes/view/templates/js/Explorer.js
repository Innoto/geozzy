var geozzy = geozzy || {};


geozzy.explorer = function( opts ) {

  var that = this;


  //  Options

  that.options = {
    explorerAPIHost: '/api/explorer/',
    explorerName: 'default',

    // cache times (in seconds)
    cacheTimeIndex: 120,

    debug: false,

    // events
    filterChangeEvent: function(){},
    filteringEndEvent: function(){},
    firstLoadEvent: function(){}
  }
  $.extend(true, that.options, opts);


  //  Debuger

  that.timeDebugerMain = new TimeDebuger( {debug: that.options.debug, instanceName:'Explorer main'} );
  that.timeDebugerExtended =  new TimeDebuger( {debug: that.options.debug, instanceName:'Explorer extended data'} );


  // Resource Collections and Indexes

  that.resourceIndex = false;

  that.resourceMinimalList = new ExplorerResourceMinimalCollection();
  that.resourcePartialList =  new ExplorerResourcePartialCollection();


  // Displays

  that.displays = {
    map: false,
    mapInfo: false,
    activeList: false,
    pasiveList: false
  }

  // filters
  that.filters = [ ];






  //
  // First Execution
  //

  that.exec = function() {

    // set multiple fetches
    that.resourceMinimalList.url = that.options.explorerAPIHost + 'explorer/' + that.options.explorerName+ '/request/minimal/updatedfrom/false';


    // render filters
    if( that.filters.length > 0 ) {
      $.each( that.filters, function(i,e){
        e.render();
      });
    }


    that.resourceMinimalList.fetch(
      {
        cache: true,
        expires: that.options.cacheTimeIndex ,
        success: function() {



          that.timeDebugerMain.log('&nbsp;- Fetch first resource index with '+ that.resourceMinimalList.length + ' elements');

          that.resourceIndex = new Backbone.Obscura(that.resourceMinimalList);
          that.timeDebugerMain.log( '&nbsp;- Resources Indexed first time' );


          that.applyFilters();

        }
      }

    );

  }


  //
  // Apply filters
  //

  that.addFilter = function( filter ) {
    filter.parentExplorer = this;
    that.filters.push( filter );

  }

  that.applyFilters = function() {


    that.timeDebugerMain.reset();
    that.timeDebugerExtended.reset();

    that.resourceIndex.removePagination();

    // Set filters for current index
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
        ret = true
      }

      // if matches number is same of filters number
      return ret;

    });
    that.timeDebugerMain.log( '&nbsp;- Resultado filtrado final '+ that.resourceIndex.length + ' Records' );


    that.render();
  }

  //
  // Display methods
  //

  that.addDisplay = function( displayObj ){
    if( displayObj.displayType == 'map' ) {
      that.displays.map = displayObj;
      that.displays.map.parentExplorer = that;
    }
    else
    if( displayObj.displayType == 'mapInfo' ) {
      that.displays.mapInfo = displayObj;
      that.displays.mapInfo.parentExplorer = that;
    }
    else
    if( displayObj.displayType == 'activeList' ) {
      that.displays.activeList = displayObj;
      that.displays.activeList.parentExplorer = that;
    }
    else
    if( displayObj.displayType == 'pasiveList' ) {
      that.displays.pasiveList = displayObj;
      that.displays.pasiveList.parentExplorer = that;
    }
    else {
      console.log('Geozzy explorer ERROR: Display type key not found');
    }
  }



  that.render = function( dontRenderMap ) {

    var resourcesToLoad = [];

    if(that.displays.map ) {
      if( that.displays.map.isReady() ){
        resourcesToLoad = $.merge( that.displays.map.getVisibleResourceIds() , resourcesToLoad);
      }

      if( !dontRenderMap ) {
        that.displays.map.render();
      }
    }

    if(that.displays.activeList) {
      resourcesToLoad = $.merge( that.displays.activeList.getVisibleResourceIds() , resourcesToLoad);
    }

    if(that.displays.pasiveList) {
      that.displays.pasiveList.currentPage = 0;
      resourcesToLoad = $.merge( that.displays.pasiveList.getVisibleResourceIds() , resourcesToLoad);
    }




    // Advanced Fetch
    that.timeDebugerExtended.log('Starting second data fetch at')


    that.fetchPartialList(
      resourcesToLoad,
      function() {
        that.timeDebugerExtended.log( '&nbsp;- Fetch partial resource data' );

        if(that.displays.activeList) {
          that.displays.activeList.render();
        }

        if(that.displays.pasiveList) {
          that.displays.pasiveList.render();
        }

        that.timeDebugerExtended.log( '&nbsp;- Render lists' );

      }

    );
  },


  that.fetchPartialList = function( resourcesToLoad, success ) {
    that.resourcePartialList.fetchAndCache({
      ids: resourcesToLoad,
      url: that.options.explorerAPIHost + 'explorer/' + that.options.explorerName+ '/request/partial/updatedfrom/false',
      success: function() {
        success();
      }
    });
  }





}
