
var app = app || {};

var CategoryEditorView = Backbone.View.extend({

  events: {
    "click .newTaxTerm": "addCategory" ,
    "click .btnEditTerm" : "editCategory",
    "click .btnCancelTerm" : "cancelEditCategory",
    "click .btnSaveTerm" : "saveEditCategory",
    "click .btnDeleteTerm" : "removeCategory" ,
    "click .cancelTerms" : "cancelTerms" ,
    "click .saveTerms" : "saveTerms" 
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
    this.$el.find('.listTerms').html('');

    var notDeletedCategoryTerms = that.categoryTerms.search( { deleted:0 } );

    notDeletedCategoryTerms.sortByField('weight');

    var categoriesParents = notDeletedCategoryTerms.search({parent:0 }).toJSON();

    _.each( categoriesParents , function(item){
      that.$el.find('.listTerms').append( that.listTemplate({ term: item }) );

      var categoriesChildren = notDeletedCategoryTerms.search({parent:item.id}).toJSON();



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
      element.set({parent:0});
      element.set({ weight: itemWeight });
      if(e.children){
        _.each( e.children , function( eCh , iCh ){
          itemWeight++;
          var elementSon = that.categoryTerms.get(eCh.id);
          elementSon.set({ weight: itemWeight, parent:e.id });
        });
      }
      itemWeight++;
    });
  },


/*Edición de un categoria*/

  removeCategory: function( el ) {
    var that = this;

    var tId = $(el.currentTarget).attr('data-id');

    var c = that.categoryTerms.get( tId )
    c.set({deleted:1})
    
    _.each( that.categoryTerms.search({ parent: tId }).toJSON(), function( e, i ) {
      console.log(e);
    });

    //c.destroy();
    that.updateList();

   },

  addCategory: function() {
    var that = this;

    var newTerm = that.$el.find('.newTaxTermName').val();
    that.$el.find('.newTaxTermName').val('');

    if(newTerm != ''){
      //that.categoryTerms.sortByField('weight').last().get('weight') ;
      that.categoryTerms.sortByField('weight');

      var maxWeight = that.categoryTerms.last().get('weight');

      that.categoryTerms.add({ name:newTerm, taxgroup:  that.category.get('id'), weight:maxWeight+1 }).save().done( function(){that.updateList()} );
      //that.categoryTerms.last();
    }
  },

  editCategory: function( el ) {
    var that = this;

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
  },

  saveTerms: function() {
    var that = this;
that.categoryTerms.save();
    _.each( that.categoryTerms.search({ deleted:false }).toJSON() , function( e , i ){ 
      //console.log(e );
    });
  },

  cancelTerms: function() {
    var that = this;
    that.initialize( that.category );
  }





});
