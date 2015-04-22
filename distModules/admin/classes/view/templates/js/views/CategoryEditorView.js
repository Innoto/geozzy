
var app = app || {};

var CategoryEditorView = Backbone.View.extend({

  events: {
    "click .newTaxTerm": "addCategory" ,
    "click .list-group-item .btnEditTerm" : "editCategory",
    "click .list-group-item .btnCancelTerm" : "cancelEditCategory",
    "click .list-group-item .btnSaveTerm" : "saveEditCategory",
    "click .list-group-item .btnDeleteTerm" : "removeCategory" ,
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
    var that = this;

    this.baseTemplate = _.template( $('#taxTermEditor').html() );
    this.$el.html( this.baseTemplate(that.category.toJSON() ) );
    this.$el.find('.dd').nestable(  );
  },

  updateList: function() {
    this.listTemplate = _.template( $('#taxTermEditorItems').html() );
    this.$el.find('.listTerms').html( this.listTemplate({ terms: this.categoryTerms.toJSON() }) );


//Probando subida


  },

  removeCategory: function( el ) {
    var that = this;

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
  },

  editCategory: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('termId');
    var catRow = that.$el.find('.list-group-item[termId="' + termId + '"]' );

    catRow.find('.rowShow').hide();
    catRow.find('.rowEdit').show();
  },

  saveEditCategory: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('termId');
    var catRow = that.$el.find('.list-group-item[termId="' + termId + '"]' );

    var catTermName = catRow.find('.rowEdit .editTermInput').val();

    var term = this.categoryTerms.get( termId );
    term.set( { name:catTermName } );

    term.save();


    that.updateList();

  },


  cancelEditCategory: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('termId');
    var catRow = that.$el.find('.list-group-item[termId="' + termId + '"]' );
    that.updateList();

  }



});