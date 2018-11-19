var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {}; }


geozzy.explorerComponents.resourcePartialCollection = Backbone.Collection.extend({
  url: false,
  useLocalStorage: true,
  model: geozzy.explorerComponents.resourcePartialModel,
  lastCacheUpdate: false,
  options:{},
  allResourcesLoading: false,
  allResourcesLoaded: false,


  initialize: function( opts ) {
    var that = this;

    that.getLocalStorage();

  },

  fetchByIds: function( params ) {
    var that = this;


    if( that.lastCacheUpdate === false ) {

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
            that.fetchFull( false );
          }
        });
      }
      else {
        if(params.success) {
          params.success();
        }
      }
    }
    else {
      that.fetchFull( that.lastCacheUpdate );
      params.success(  );
    }

  },

  fetchFull: function( updatedfrom ) {
    var that = this;




    if( that.allResourcesLoading === false  && that.allResourcesLoaded === false ) {
      that.allResourcesLoading = true;
      that.fetch({
        type: 'POST',
        remove: false,
        data:{updatedfrom: updatedfrom},
        success: function( list ) {

          that.allResourcesLoaded = true;
          that.allResourcesLoading = false;

          cogumelo.log('Geozzy explorer loaded ' + that.length + ' resources');
          that.saveLocalStorage();

        }
      });

    }
  },




  getLocalStorage: function( ) {
    var that = this;

    if( that.useLocalStorage === true && typeof Storage !== "undefined" && that.url != false) {
      var lsData;
      var lsDataParsed = false;
      if( ( lsData = localStorage.getItem( that.url ) ) !== null ){

        try{
          lsDataParsed = JSON.parse( lsData );

        }
        catch(e){
          cogumelo.log('Geozzy exlorer, failed trying to get localstorage data:' + e);
          lsDataParsed = false;
        }

        that.reset();
        that.lastCacheUpdate = lsDataParsed.lastUpdate;

        that.set( lsDataParsed.resources );

      }

    }

  },

  saveLocalStorage: function( ) {
    var that = this;

    if( that.useLocalStorage === true && typeof Storage !== "undefined" && that.url != false) {

      localStorage.removeItem( that.url );


      localStorage.setItem( that.url, JSON.stringify({ lastUpdate: Math.floor(Date.now() / 1000) , resources: that.toJSON() }) );

    }


  }

});
