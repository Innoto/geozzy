var geozzy = geozzy || {};


geozzy.rExtMapWidgetFormPositionView  = Backbone.View.extend({
  parent: false,

  events: {
  },

  initialize: function() {
    var that = this;
    that.latInput = that.$el.find(".lat input");
    that.lonInput = that.$el.find(".lon input");
    that.zoomInput = that.$el.find(".zoom input");
    that.addressInput = that.$el.find(".address");
  },

  getToolbarButton: function() {
    var that = this;
    return {
      icon: '',
      iconHover:'',
      iconSelected: '',
      onclick: function() {
        that.startEdit();
      }
    };
  },

  startEdit: function() {
    var that = this;
    return false;
  }

});
