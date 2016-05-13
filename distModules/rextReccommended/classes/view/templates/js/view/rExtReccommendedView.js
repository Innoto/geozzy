var geozzy = geozzy || {};

if(!geozzy.rextReccommended) geozzy.rextReccommended={};

geozzy.rextReccommended.reccommendedView = Backbone.View.extend({
  template: _.template($('#recommendedListTemplate').html()),
  el: '.rExtReccommendedList',

  events: {

  },
  initialize: function(  ) {
    var that = this;
    that.render();
  },
  render: function() {
    var that = this;
    $(that.el).append(that.template({
      id:39,
      title:'El castaño dormilón',
      image:'/cgmlImg/75/fast_cut/75.jpg',
      urlAlias:'alojamientos/el-castano-dormilon',
      shortDescription:'Casa rural en la comarca del Ortegal'
    }));
  }

});
