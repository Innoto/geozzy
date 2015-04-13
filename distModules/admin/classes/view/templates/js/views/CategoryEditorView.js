
var app = app || {};

var CategoryEditorView = Backbone.View.extend({

  events: {
    "click .newTaxTerm": "addCategory"  
  },

  render: function() {
    this.baseTemplate = _.template( $('#taxTermEditor').html() );
    this.$el.html( this.baseTemplate() );
  },

  updateList: function() {

  },

  addCategory: function() {
    var newTerm = this.$el.find('.newTaxTermName').val();
    this.$el.find('.newTaxTermName').val('');


  }

});