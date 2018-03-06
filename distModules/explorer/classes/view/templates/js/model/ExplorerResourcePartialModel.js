var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }

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

    if( that.get('urlAlias') === false) {
      r = 'resource/' + that.get('id');
    }
    else  {
      r = that.get('urlAlias');
    }

    return r;
  }

});
