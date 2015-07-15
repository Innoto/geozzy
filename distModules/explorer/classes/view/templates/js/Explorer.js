var geozzy || geozzy;


geozzy.explorer = function( options ) {

  var that = this;

  var defaultOptions = {
    explorerHost: '/'
    explorerId: false
  };

  that.resourceIndexCollection = false;
  that.resourceCollection = false;
  that.filterCollections = [];

  that.viewerViews = [];
  that.filterViews = [];


  that.addFilter = function( filter ) {
    that.filterCollections.push( filter );
  }



  that.exec = function() {

    // set multiple fetches
    var fetches = _.invoke( that.filterCollections.push( that.resourceIndexCollection ) , 'fetch');

    // when fetches result
    $.when.apply($, fetches).done(function() {
      alert('TODO CARGADO')
    });

  }

}
