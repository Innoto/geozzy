
var app = app || {};

var MenuEditorView = Backbone.View.extend({

  events: {
    "click .newTaxTerm": "addMenuterm" ,
    "click .btnEditTerm" : "editMenuterm",
    "click .btnCancelTerm" : "cancelEditMenuterm",
    "click .btnSaveTerm" : "saveEditMenuterm",
    "click .btnDeleteTerm" : "removeMenuterm" ,
    "click .cancelTerms" : "cancelTerms" ,
    "click .saveTerms" : "saveTerms"
  },

  menuTerms : false,
  listTemplate : false,

  initialize: function() {
    var that = this;
    that.menuTerms = new MenuCollection();
    that.menuTerms.url = that.menuTerms.baseUrl;
    that.menuTerms.fetch(
      {
        success: function() {
          that.updateList();
        }
      }
    );
  },

  render: function() {
    var that = this;

    this.baseTemplate = _.template( $('#menuTermEditor').html() );
    this.$el.html( this.baseTemplate(that.menuTerms.toJSON() ) );

    that.saveChangesVisible(false);

  },

  updateList: function() {
    var that = this;
    that.listTemplate = _.template( $('#menuTermEditorItem').html() );
    that.$el.find('.listTerms').html('');

    var notDeletedMenuTerms = that.menuTerms.search( { deleted:0 } );
    var menuParents = notDeletedMenuTerms.search({ parent:0 }).toJSON();
    _.each( menuParents , function(item){
      that.$el.find('.listTerms').append( that.listTemplate({ term: item }) );
      that.drawChildrenList( item.id, notDeletedMenuTerms );
    });

    var nestableParams = {
      'handleClass': 'dd-handle',
      'dragClass': "gzznestable dd-dragel",
      callback: function(l, e) {
        that.saveList();
      }
    };
    this.$el.find('.dd').nestable(nestableParams);

  },

  drawChildrenList: function( idParent, notDeletedMenuTerms ) {
    var that = this;
    var menuChildren = notDeletedMenuTerms.search({parent:idParent}).toJSON();
    if( menuChildren.length > 0 ){
      that.$el.find('.listTerms li[data-id="'+idParent+'"]').append('<ol class="dd-list"></ol>');
      _.each( menuChildren , function(itemchildren){
        that.$el.find('.listTerms li[data-id="'+idParent+'"] .dd-list').first().append(
          that.listTemplate({ term: itemchildren })
        );
        that.drawChildrenList( itemchildren.id , notDeletedMenuTerms );
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
      var element = that.menuTerms.get(e.id);
      element.set({ weight: itemWeight, parent: parent });
      if(e.children){
        that.saveItem( e.id, e.children );
      }
      itemWeight++;
    });
  },

/*Edición de un categoria*/

  removeMenuterm: function( el ) {
    var that = this;

    var tId = parseInt($(el.currentTarget).attr('data-id'));

    var c = that.menuTerms.get( tId );
    c.set({deleted:1});

    that.menuTerms.search({ parent: tId }).each( function( e,i  ) {
      e.set({deleted:1});
    });

    that.updateList();
    that.saveChangesVisible(true);
   },

  addMenuterm: function() {
    var that = this;
    Backbone.history.navigate('menu/term/create', {trigger:true});
  },

  editMenuterm: function( el ) {
    var that = this;
    Backbone.history.navigate('menu/term/edit/'+$(el.currentTarget).attr('data-id'), {trigger:true});
  },

  saveEditMenuterm: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('data-id');
    var catRow = that.$el.find('li[data-id="' + termId + '"]' );
    var catTermName = catRow.find('.rowEdit .editTermInput').val();
    var term = this.menuTerms.get( termId );
    term.set( { name:catTermName } );
    term.save();

    that.updateList();
  },


  cancelEditMenuterm: function( el ) {
    var that = this;

    var termId =  $(el.currentTarget).attr('data-id');
    var catRow = that.$el.find('li[data-id="' + termId + '"]' );
    that.updateList();
  },

  saveTerms: function() {
    var that = this;
    that.menuTerms.save();
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
