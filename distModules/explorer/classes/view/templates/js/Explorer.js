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
  that.resourceCurrentIndex = false;
  that.resourceMinimalList = new ExplorerResourceMinimalCollection();
  that.resourcePartialList =  new ExplorerResourcePartialCollection();


  // Displays

  that.displays = {
    map: false,
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
    that.resourceMinimalList.url = that.options.explorerAPIHost + that.options.explorerName+ '/minimal';

    that.resourceMinimalList.fetch(
      {
        cache: true,
        expires: that.options.cacheTimeIndex ,
        success: function() {



          that.timeDebugerMain.log('&nbsp;- Fetch first resource index with '+ that.resourceMinimalList.length + ' elements');

          that.resourceIndex = new Backbone.Obscura(that.resourceMinimalList);
          that.timeDebugerMain.log( '&nbsp;- Resources Indexed first time' );



          that.resourceCurrentIndex = new Backbone.Obscura(that.resourceIndex);
          that.timeDebugerMain.log( '&nbsp;- Clonado indice' );


          that.timeDebugerMain.log( '> Carga inicial concluida' );

          that.applyFilters();

        }
      }

    );

  }


  //
  // Apply filters
  //

  that.addFilter = function( filter ) {
    that.filters.push( filter );
  }

  that.applyFilters = function() {

      // Set filters for current index
      that.resourceCurrentIndex.filterBy( function(model) {
        var matches = false;
        $.each( that.filters, function(i, filter){

          if( filter.filterAction( model ) ) {
            matches = true;
            return;
          }
        });

        return matches;

      });
      that.timeDebugerMain.log( '&nbsp;- Resultado filtrado final '+ that.resourceCurrentIndex.length + ' Records' );


      that.render();
  }

  //
  // Display methods
  //

  that.addDisplay = function( diplayId, displayObj ){
    if( diplayId == 'map' ) {
      that.displays.map = displayObj;
      that.displays.map.parentExplorer = that;
    }
    else
    if( diplayId == 'activeList' ) {
      that.displays.activeList = displayObj;
      that.displays.activeList.parentExplorer = that;
    }
    else
    if( diplayId == 'pasiveList' ) {
      that.displays.pasiveList = displayObj;
      that.displays.pasiveList.parentExplorer = that;
    }
    else {
      console.log('Geozzy explorer ERROR: Display type key not found');
    }
  }



  that.render = function() {
    var resourcesToLoad = [];

    if(that.displays.map) {
      //resourcesToLoad = $.merge( that.displays.map.getVisibleResourceIds() , resourcesToLoad);
      that.displays.map.render();
    }

    if(that.displays.activeList) {
      resourcesToLoad = $.merge( that.displays.activeList.getVisibleResourceIds() , resourcesToLoad);

    }

    if(that.displays.pasiveList) {
      //that.displays.pasiveList.getVisibleResourceIds();
    }


    // Advanced Fetch
    that.timeDebugerExtended.log('Starting second data fetch at')

    that.resourcePartialList.fetchAndCache({
      ids: resourcesToLoad,
      url: that.options.explorerAPIHost + that.options.explorerName+ '/partial',
      success: function() {
        that.timeDebugerExtended.log( '&nbsp;- Fetch partial resource data' );

        if(that.displays.activeList) {
          that.displays.activeList.render();
        }

        that.timeDebugerExtended.log( '&nbsp;- Render lists' );
      }
    });


  }



}
