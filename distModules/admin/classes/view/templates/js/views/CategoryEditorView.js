
var app = app || {};

var CategoryEditorView = Backbone.View.extend({

  events: {
    "click .newTaxTerm": "addCategory" ,
    "click .btnEditTerm" : "editCategory",
    "click .btnCancelTerm" : "cancelEditCategory",
    "click .btnSaveTerm" : "saveEditCategory",
    "click .btnDeleteTerm" : "removeCategory" ,
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
    //this.$el.find('.dd').nestable( { 'maxDepth':2 } );
  },

  updateList: function() {
    var that = this;
//this.listTemplate = _.template( $('#taxTermEditorItems').html() );
//this.$el.find('.listTerms').html( this.listTemplate({ terms: this.categoryTerms.toJSON() }) );
//var categoryParents = this.categoryTerms.search({parent:false}).toJSON();

    this.listTemplate = _.template( $('#taxTermEditorItem').html() );
    this.$el.find('.listTerms').html();
    var categories = this.categoryTerms.toJSON();


    _.each( categories , function(item){
      that.$el.find('.listTerms').append( that.listTemplate({ term: item }) );
    });

    var categoriesParents = this.categoryTerms.search({parent:false}).toJSON();
    var jsonCategories = [];
    _.each( categoriesParents , function(item){
        var parent = item;
        var children = that.categoryTerms.search({parent:item.id}).pluck("id");
        var childrenJson = [];
        if(children.length > 0){
          _.each( children, function( chil ){
            childrenJson.push({ "id": chil });
          });
          var jsonC = { "id": item.id , "children": childrenJson }
        }else{
          var jsonC = { "id": item.id }
        }
        jsonCategories.push(jsonC);
    });
    console.log(jsonCategories);
    this.$el.find('.dd').nestable('serialize', { 'json': jsonCategories, 'maxDepth':2 } );

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
    var catRow = that.$el.find('[termId="' + termId + '"]' );

    catRow.find('.rowShow').hide();
    catRow.find('.rowEdit').show();
  },

  saveEditCategory: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('termId');
    var catRow = that.$el.find('[termId="' + termId + '"]' );

    var catTermName = catRow.find('.rowEdit .editTermInput').val();

    var term = this.categoryTerms.get( termId );
    term.set( { name:catTermName } );

    term.save();


    that.updateList();

  },


  cancelEditCategory: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('termId');
    var catRow = that.$el.find('[termId="' + termId + '"]' );
    that.updateList();

  }



});