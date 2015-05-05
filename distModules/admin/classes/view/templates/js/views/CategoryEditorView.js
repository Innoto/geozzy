
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





  },

  updateList: function() {
    var that = this;

    this.listTemplate = _.template( $('#taxTermEditorItem').html() );
    this.$el.find('.listTerms').html();

    that.categoryTerms.sortByField('weight');
    var categoriesParents = that.categoryTerms.search({parent:false}).toJSON();

    _.each( categoriesParents , function(item){
      that.$el.find('.listTerms').append( that.listTemplate({ term: item }) );

      that.categoryTerms.sortByField('weight');
      var categoriesChildren = that.categoryTerms.search({parent:item.id}).toJSON();


      if( categoriesChildren.length > 0 ){
        that.$el.find('.listTerms li[data-id="'+item.id+'"]').append('<ol class="dd-list"></ol>');
        _.each( categoriesChildren , function(itemchildren){
          that.$el.find('.listTerms li[data-id="'+itemchildren.parent+'"] .dd-list').append(
            that.listTemplate({ term: itemchildren })
          );
        });
      }
    });

    this.$el.find('.dd').nestable({ 'maxDepth': 2 , callback: function(l, e) {
      that.saveList();
    } });

  },

  saveList: function(){
    var that = this;
    var jsonCategories = $('#taxTermListContainer').nestable('serialize');
    var itemWeight = 0;
    _.each( jsonCategories , function( e , i ){

      var element = that.categoryTerms.get(e.id);
      element.set({ weight: itemWeight });
      if(e.children){
        _.each( e.children , function( eCh , iCh ){
          itemWeight++;
          element.set({ weight: itemWeight, parent:e.id });
        });
      }
      itemWeight++;
    });
  },


/*Edición de un categoria*/

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
alert("oals");
    var termId =  $(el.currentTarget).attr('data-id');
    var catRow = that.$el.find('li[data-id="' + termId + '"]' );

    catRow.find('.rowShow').hide();
    catRow.find('.rowEdit').show();
  },

  saveEditCategory: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('data-id');
    var catRow = that.$el.find('li[data-id="' + termId + '"]' );
    var catTermName = catRow.find('.rowEdit .editTermInput').val();
    var term = this.categoryTerms.get( termId );
    term.set( { name:catTermName } );
    //term.save();

    that.updateList();
  },


  cancelEditCategory: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('data-id');
    var catRow = that.$el.find('li[data-id="' + termId + '"]' );
    that.updateList();

  }



});
