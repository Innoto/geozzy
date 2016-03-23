var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }


geozzy.explorerComponents.resourcePartialCollection = Backbone.Collection.extend({
  url: false,
  model: geozzy.explorerComponents.resourcePartialModel,
  lastUpdate: false,
  options:{},
  allResourcesLoading: false,
  allResourcesLoaded: false,


  initialize( opts ) {
    var that = this;
    var options = {
      useLocalStorage: true,
      url: false
    }
    that.options = $.extend(true, options, opts);

    that.url = that.options.url;
    that.getLocalStorage();

  },

  fetchIds: function( params ) {
    var that = this;
    var allIdsInCollection = true;



    if( params.ids.length === 1 && that.get(params.ids[0]) ){
      if(params.success) { params.success(); }
    }
    else
    if( that.allResourcesLoaded === false ) {
      that.fetch({
        data: {ids: params.ids},
        type: 'POST',
        remove: false,
        success: function( list ) {

          if(params.success) {
            params.success();
          }

          if( that.allResourcesLoading === false  && that.allResourcesLoaded === false ) {

            that.fetchFull();

          }
        }
      });
    }
    else {
      if(params.success) {
        params.success();
      }
    }
  },

  fetchFull: function(  ) {
    var that = this;

    that.allResourcesLoading = true;

    that.fetch({
      type: 'POST',
      remove: false,
      success: function( list ) {
        that.allResourcesLoaded = true;
        that.allResourcesLoading = false;

        console.log('Geozzy explorer loaded ' + that.length + ' resources');
      }
    });
  }




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

});
