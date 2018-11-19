var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {}; }

geozzy.explorerComponents.resourcePartialModel = Backbone.Model.extend({
  defaults: {
    id: false,
    title: false,
    description: false,
    urlAlias: false
  },

  getUrl: function() {
    var that = this;
    var r;


    var a = that.get('urlAlias');

    if( a == false || a == null || a == '' ) {
      r = 'resource/' + that.get('id');
    }
    else  {
      r = a;
    }

    return r;
  }

});
