
var app = app || {};

var CategoryEditorView = Backbone.View.extend({

  events: {
    "click .newTaxTerm": "addCategory" ,
    "click .btnEditTerm" : "editCategory",
    "click .btnCancelTerm" : "cancelEditCategory",
    "click .btnSaveTerm" : "saveEditCategory",
    "click .btnDeleteTerm" : "removeCategoryterm" ,
    "click .cancelTerms" : "cancelTerms" ,
    "click .saveTerms" : "saveTerms"
  },

  category: false,
  categoryTerms : false,
  listTemplate: false,



  initialize: function( category ) {
    var that = this;
    that.categoryTerms = new CategorytermCollection();

    that.category = category;

    that.categoryTerms.url = that.categoryTerms.baseUrl;

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

    that.saveChangesVisible(false);

  },

  updateList: function() {
    var that = this;

    that.listTemplate = _.template( $('#taxTermEditorItem').html() );
    that.$el.find('.listTerms').html('');

    var notDeletedCategoryTerms = that.categoryTerms.search( { deleted:0 } );
    var categoriesParents = notDeletedCategoryTerms.search({parent:0 }).toJSON();

    _.each( categoriesParents , function(item){
      that.$el.find('.listTerms').append( that.listTemplate({ term: item }) );
      that.drawChildrenList( item.id, notDeletedCategoryTerms );
    });

    var nestableParams = {
      'maxDepth': that.category.get('nestable')+1,
      'handleClass': 'dd-handle',
      'dragClass': "gzznestable dd-dragel",
      callback: function(l, e) {
        that.saveList();
      }
    };
    if(that.category.get('sortable') === 0){
      nestableParams.handleClass = "no-handle-gzz";
    }
    this.$el.find('.dd').nestable(nestableParams);

  },

  drawChildrenList: function( idParent, notDeletedCategoryTerms ) {
    var that = this;
    var menuChildren = notDeletedCategoryTerms.search({parent:idParent}).toJSON();
    if( menuChildren.length > 0 ){
      that.$el.find('.listTerms li[data-id="'+idParent+'"]').append('<ol class="dd-list"></ol>');
      _.each( menuChildren , function(itemchildren){
        that.$el.find('.listTerms li[data-id="'+idParent+'"] .dd-list').first().append(
          that.listTemplate({ term: itemchildren })
        );
        that.drawChildrenList( itemchildren.id , notDeletedCategoryTerms );
      });
    }
  },

  saveList: function(){

    var that = this;
    var jsonCategories = $('#taxTermListContainer').nestable('serialize');
    that.saveItem( 0, jsonCategories );
    that.saveChangesVisible(true);
  },

  saveItem: function ( parent, data ){
    var that = this;
    var itemWeight = 0;
    _.each( data , function( e , i ){
      var element = that.categoryTerms.get(e.id);
      element.set({ weight: itemWeight, parent: parent });
      if(e.children){
        that.saveItem( e.id, e.children );
      }
      itemWeight++;
    });
  },


/*Edición de un categoria*/

  removeCategoryterm: function( el ) {
    var that = this;

    var tId = parseInt($(el.currentTarget).attr('data-id'));

    var c = that.categoryTerms.get( tId );
    c.set({deleted:1});

    that.categoryTerms.search({ parent: tId }).each( function( e,i  ) {
      e.set({deleted:1});
    });

    that.updateList();
    that.saveChangesVisible(true);
   },

  addCategory: function() {
    var that = this;
    Backbone.history.navigate('category/'+that.category.id+'/term/create', {trigger:true});

/*    var that = this;

    var newTerm = that.$el.find('.newTaxTermName').val();
    that.$el.find('.newTaxTermName').val('');

    if(newTerm != ''){

      if( !that.categoryTerms.last() ){
        var maxWeight = 0;
      }
      else {
        var maxWeight = that.categoryTerms.last().get('weight');
      }

      that.categoryTerms.add({ name:newTerm, taxgroup:  that.category.get('id'), weight:maxWeight }).save().done( function(){that.updateList()} );
      //that.categoryTerms.last();
    }*/
  },

  editCategory: function( el ) {
    var that = this;


    Backbone.history.navigate('category/'+that.category.id+'/term/edit/'+$(el.currentTarget).attr('data-id'), {trigger:true});

    /*
    var that = this;

    var termId =  $(el.currentTarget).attr('data-id');
    var catRow = that.$el.find('li[data-id="' + termId + '"]' );

    catRow.find('.rowShow').hide();
    catRow.find('.rowEdit').show();
    */
  },

  saveEditCategory: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('data-id');
    var catRow = that.$el.find('li[data-id="' + termId + '"]' );
    var catTermName = catRow.find('.rowEdit .editTermInput').val();
    var term = this.categoryTerms.get( termId );
    term.set( { name:catTermName } );
    term.save();

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
    that.saveChangesVisible(false);
  },

  cancelTerms: function() {
    var that = this;
    that.saveChangesVisible(false);
    that.initialize( that.category );
  },

  saveChangesVisible: function( visible ) {

    if( visible ) {
      this.$el.find('.saveChanges').show();
    }
    else{
      this.$el.find('.saveChanges').hide();
    }

  }



});
