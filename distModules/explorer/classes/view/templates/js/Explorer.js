var geozzy = geozzy || {};


geozzy.explorer = function( opts ) {

  var that = this;


  //
  //  Options
  //

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


  //
  //  Debuger
  //

  that.timeDebuger = new TimeDebuger( {debug: that.options.debug} );


  //
  // Resource Collections and Indexes
  //

  that.resourceIndex = false;
  that.resourceCurrentIndex = false;
  that.resourceMinimalList = new ExplorerResourceMinimalCollection();
  that.resourcePartialList =  new ExplorerResourcePartialCollection();



  //
  // Displays
  //

  that.displays = {
    map: false,
    activeList: false,
    pasiveList: false
  }

  that.addDisplay = {};

  that.addDisplay.map = function( obj ) {
    that.displays.map = obj;
    that.displays.map.parent = that;
  }

  that.addDisplay.activeList = function( obj ) {
    that.displays.activeList = obj;
    that.displays.activeList.parent = that;
  }

  that.addDisplay.pasiveList = function( obj ) {
    that.displays.pasiveList = obj;
    that.displays.pasiveList.parent = that;
  }


  that.renderDisplays = function() {
    var resourcesToLoad = [];

    if(that.displays.map) {
      that.displays.map.render();
      resourcesToLoad = that.displays.getVisibleResources();
    }

    if(that.displays.activeList) {
      that.displays.activeList.getVisibleResources();
    }

    if(that.displays.pasiveList) {
      that.displays.pasiveList.getVisibleResources();
    }


    // Advanced Fetch
    /*

        if(that.displays.activeList) {
          that.displays.activeList.render();
        }

        if(that.displays.pasiveList) {
          that.displays.map.pasiveList();
        }
    */

    // add markerssssSSSSSSSSSSSSSSSSSSSSSS
    that.resourceCurrentIndex.setPerPage(600);
    $.each( that.resourceCurrentIndex.toJSON(), function(i,e) {

      new google.maps.Marker({
        position: new google.maps.LatLng( e.lat, e.lng ),
        map: resourceMap,
        title: toString(e.id)

      });
    });
    that.timeDebuger.log( '&nbsp;- Pintado Mapa '+that.resourceCurrentIndex.length+ 'recursos' );

    that.resourcePartialList.fetchAndCache({
        'url': that.options.explorerAPIHost + that.options.explorerName+ '/partial',
        'success': function() {
          //that.timeDebuger.log( '&nbsp;- Fetch partial resource data' );

          //that.resourceCurrentIndex.setPerPage(100);

          //that.timeDebuger.log( '&nbsp;- pagination' );
          $.each( that.resourceCurrentIndex.pluck('id'), function(i,e){
            $('#explorerList').append('<div>'+ that.resourcePartialList.get( e ).get('title') +'</div><br>');
          });


//          that.timeDebuger.log( '&nbsp;- Render lists' );

  //        that.timeDebuger.log( '> Render displays concluido' );
        }
    });


  }

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



          that.timeDebuger.log('&nbsp;- Fetch first resource index with '+ that.resourceMinimalList.length + ' elements');

          that.resourceIndex = new Backbone.Obscura(that.resourceMinimalList);
          that.timeDebuger.log( '&nbsp;- Resources Indexed first time' );


          // when map exist, set current index as map context
          if( that.displays.map ) {

          }
          else {
            that.resourceCurrentIndex = new Backbone.Obscura(that.resourceIndex);
            that.timeDebuger.log( '&nbsp;- Clonado indice' );
          }

          that.timeDebuger.log( '> Carga inicial concluida' );

          that.applyFilters();

        }
      }

    );

  }


  //
  // Apply filters
  //

  that.applyFilters = function() {

      // Set filters for current index
      that.resourceCurrentIndex.filterBy( function(model) {
          var terms =  model.get('terms');
          var diff = $( terms ).not( [14,10,25,37,19,47]);
          return (diff.length != terms.length );
      });
      that.timeDebuger.log( '&nbsp;- Resultado filtrado final '+ that.resourceCurrentIndex.length + ' Records' );


      that.renderDisplays();
  }


}






















          ////////////////////////////////////////////////////////////////////////////////////////////
          //////////////////////////////////////////////////////////////////////////////////////////
          ////////////////////////////////////////////////////////////////////////////////////////////
          //////////////////////////////////////////////////////////////////////////////////////////
/*
          that.timeDebuger.log('Index - loaded '+ that.resourceSimpleList.length + ' resources');


          var proxy = new Backbone.Obscura(that.resourceSimpleList);
          that.timeDebuger.log( 'Pimeiro ndexado' );
          // Set the transformations on the original collection
          proxy.filterBy(function(model) {
              return model.get('lat') > 40.3949 && model.get('lng') > -7.2256 && model.get('lat') < 45 && model.get('lng') > -6.2256;

            });

          that.timeDebuger.log( 'Resultado filtrado:' + proxy.length + ' Records' );

          var proxy2 = new Backbone.Obscura(proxy);

          that.timeDebuger.log( 'segundo indexado' );
          proxy2.filterBy(function(model) {

              var terms =  model.get('terms');
              var diff = $( terms ).not( [14,10,25]);

              return (diff.length != terms.length );

            });
          that.timeDebuger.log( 'Resultado segundo filtrado '+ proxy2.length + ' Records' );

          //console.log(proxy2.pluck('id'));


          //console.log(proxy.toJSON() );
*/
          ////////////////////////////////////////////////////////////////////////////////////////////
          //////////////////////////////////////////////////////////////////////////////////////////
          ////////////////////////////////////////////////////////////////////////////////////////////
          //////////////////////////////////////////////////////////////////////////////////////////
