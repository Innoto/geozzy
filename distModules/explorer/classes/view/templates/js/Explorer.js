var geozzy = geozzy || {};


geozzy.explorer = function( opts ) {

  var that = this;

  that.options = {
    explorerAPIHost: '/api/explorer/',
    explorerName: 'default',
    extraFilters: {},
    explorerId: false,

    // cache times (in seconds)
    cacheTimeIndex: 120,

    debug: false,

    // events
    filterChangeEvent: function(){},
    filteringEndEvent: function(){},
    firstLoadEvent: function(){}
  };
  $.extend(true, that.options, opts);


  that.timeDebuger = new TimeDebuger( {debug: that.options.debug} );

  that.explorerChecksum = false;
  that.resourceIndex = new ExplorerResourceCollection() ;

  that.filters = {};

  that.displays = {
    mapView: false,
    activeList: false,
    pasiveList: false
  };



  that.exec = function() {

    // set multiple fetches
    that.resourceIndex.url = that.options.explorerAPIHost + that.options.explorerName+ '/index';

    that.resourceIndex.fetch(
      {
        cache: true,
        expires: that.options.cacheTimeIndex ,
        success: function() {
          that.timeDebuger.log('Index - loaded '+ that.resourceIndex.length + ' resources');
          //console.log(that.resources.toJSON() );
          //console.log( that.resources.toJSON() );


          var proxy = new Backbone.Obscura(that.resourceIndex);
          that.timeDebuger.log( 'Pimeiro ndexado' );
          // Set the transformations on the original collection
          proxy.filterBy(function(model) {
              return model.get('lat') > 40.3949 && model.get('lng') > -7.2256 && model.get('lat') < 45 && model.get('lng') > -6.2256;

            });

          that.timeDebuger.log( 'Filtrado:' + proxy.length + ' Records' );

          var proxy2 = new Backbone.Obscura(proxy);

          that.timeDebuger.log( 'segundo indexado' );
          proxy2.filterBy(function(model) {
              return model.get('id') > 11000;

            });
          that.timeDebuger.log( 'Filtrado '+ proxy2.length + ' Records' );

          console.log(proxy2.pluck('id'));


          //console.log(proxy.toJSON() );
        }
      }

    );

  }


}
