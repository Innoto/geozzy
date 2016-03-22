var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }


geozzy.explorerComponents.resourcePartialCollection = Backbone.Collection.extend({
  url: false,
  model: geozzy.explorerComponents.resourcePartialModel,
  lastUpdate: false,
  options:{},

  initialize( opts ) {
    var that = this;
    var options = {
      useLocalStorage: true,
      url: false
    }
    that.options = $.extend(true, options, opts);

    that.url = that.options.url;
    that.getLocalStorage();
    console.log(that.url)
  },

  getLocalStorage( ) {
    var that = this;

    if( that.useLocalStorage === true && typeof Storage !== "undefined" && that.url != false) {
      var lsData;
      var lsDataParsed = false;
      if( ( lsData = localStorage.getItem( that.url ) ) !== null ){

        try{
          lsDataParsed = JSON.parse( lsData );
        }
        catch(e){
          console.log('Geozzy exlorer, failed trying to get localstorage data:' + e);
          lsDataParsed = false;
        }

        that.reset()
        that.lastUpdate = lsDataParsed.lastUpdate;
        that.add( lsDataParsed.resources );

      }

    }

  },

  saveLocalStorage( ) {
    var that = this;

    if( that.useLocalStorage === true && typeof Storage !== "undefined" && that.url != false) {
      localStorage.removeItem( that.url );
      localStorage.setItem( that.url, { lastUpdate: new Date().getTime() , resources: that.toJSON } )
    }


  },

  fetchIds: function( params ) {
    var that = this;
    var allIdsInCollection = true;


    $.each( params.ids, function(i,e) {
      if( !that.get( e ) ){
        allIdsInCollection = false;
        return;
      }
    });

    if( allIdsInCollection === true ) {
      params.success();
    }

    that.fetch({
      data: {ids: params.ids},
      type: 'POST',
      remove: false,
      success: function( list ) {
        list.each(function(resource) {
          that.add(resource);
        });


        if(params.success) {
          params.success();
          that.saveLocalStorage();
        }

      }
    });
  }

});
