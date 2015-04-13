
var app = app || {};

var CategoryEditorView = Backbone.View.extend({

  events: {
    "click .newTaxTerm": "addCategory"  
  },

  category: false,
  categoryTerms : false,

  initialize: function( category ) {
    var that = this;
    that.categoryTerms = new CategorytermCollection();

    that.category = category;

    that.categoryTerms.fetch(
      {
        data: { group: that.category.get('id') },
        success: function() {
          that.updateList();
        }
      }
    );


  },

  render: function() {
    this.baseTemplate = _.template( $('#taxTermEditor').html() );
    this.$el.html( this.baseTemplate() );
  },

  updateList: function() {
    this.listTemplate = _.template( $('#taxTermEditorItems').html() );
    this.$el.find('.listTerms').html( this.listTemplate({ terms: this.categoryTerms.toJSON() }) );
  },

  addCategory: function() {
    var that = this;

    var newTerm = that.$el.find('.newTaxTermName').val();
    that.$el.find('.newTaxTermName').val('');
  

    if(newTerm != ''){
      new TaxonomytermModel
      that.categoryTerms.add({ name:newTerm, taxgroup:  that.category.get('id') });
      that.categoryTerms.last().save().done( function(){that.updateList()} );

    }
  }

});