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
    console.log('ola');
    $(that.el).append(that.template());
  }

});
