var ResourceModel = Backbone.Model.extend({
  defaults: {
    id: false,
    type: false,
    published: false,
    title: '',
    deleted: 0,
    weight: false
  },
  destroyResourceTerm: function (options) {
    var opts = _.extend({
        url: '/api/admin/resourcesTerm/'+options+ '/resource/' +this.id
      },
      options || {});
    return Backbone.Model.prototype.destroy.call(this, opts);
  },

  saveResourceTerm: function (options) {
    return Backbone.sync('update', this, {url: '/api/admin/resourcesTerm/'+options+ '/resource/' +this.id});
  }

});
