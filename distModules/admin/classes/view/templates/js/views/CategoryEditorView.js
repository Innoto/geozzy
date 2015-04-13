
var app = app || {};

var CategoryEditorView = Backbone.View.extend({

  events: {
    "click .newTaxTerm": "addCategory" ,
    "click .list-group-item .btnDeleteTerm" : "removeCategory" ,
  },

  category: false,
  categoryTerms : false,



  initialize: function( category ) {
    var that = this;
    that.categoryTerms = new CategorytermCollection();

    that.category = category;

    console.log( that.category.get('id') )

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

  removeCategory: function( el ) {
    var that = this;

    //console.log( $(el.currentTarget).attr('termId') );
    var c = that.categoryTerms.get( $(el.currentTarget).attr('termId') )
    c.destroy();
    that.updateList();

   },

  addCategory: function() {
    var that = this;

    var newTerm = that.$el.find('.newTaxTermName').val();
    that.$el.find('.newTaxTermName').val('');
  
    if(newTerm != ''){
      that.categoryTerms.add({ name:newTerm, taxgroup:  that.category.get('id') });
      that.categoryTerms.last().save().done( function(){that.updateList()} );
    }
  }

});