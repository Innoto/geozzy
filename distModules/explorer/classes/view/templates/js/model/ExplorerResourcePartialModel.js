var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }

geozzy.explorerComponents.resourcePartialModel = Backbone.Model.extend({
  defaults: {
    id: false,
    title: false,
    description: false,
    url: false
  },

  getUrl: function() {
    var that = this;
    var r;

    if( that.get('url') === false) {
      r = 'resource/' + that.get('id');
    }
    else  {
      r = that.get('url');
    }

    return r;
  }

});
