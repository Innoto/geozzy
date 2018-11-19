
var app = app || {};

var ResourcesStarredListView = Backbone.View.extend({

  events: {
    "click .assignResourceTerm": "assignResourceTerm" ,
    "click .btnDelete" : "removeResourceStarred" ,
    "click .cancel" : "cancelResourceStarred" ,
    "click .save" : "saveResourceStarred"
  },

  starredTerm: false,
  resourcesStarred : false,



  initialize: function( starredTerm ) {
    var that = this;
    that.resourcesStarred = new ResourcesStarredCollection();
    that.starredTerm = starredTerm;

    that.resourcesStarred.fetch(
      {
        data: { taxonomyterm: that.starredTerm.get('id') },
        success: function() {
          that.updateList();
        }
      }
    );

  },

  render: function() {
    var that = this;

    this.baseTemplate = _.template( $('#resourcesStarredList').html() );
    this.$el.html( this.baseTemplate(that.starredTerm.toJSON() ) );

    that.saveChangesVisible(false);

  },

  updateList: function() {
    var that = this;

    this.listTemplate = _.template( $('#resourcesStarredItem').html() );
    this.$el.find('.listResources').html('');
    var rs = that.resourcesStarred.search({deleted:0});
    rs.sortByField('weight');
    _.each( rs.toJSON() , function(item){
      that.$el.find('.listResources').append( that.listTemplate({ resource: item }) );
    });

    this.$el.find('.dd').nestable({
      'maxDepth': 1,
      'dragClass': "gzznestable dd-dragel",
      callback: function(l, e) {
        that.saveList();
      }
    });

  },

  saveList: function(){

    var that = this;
    var jsonNestableResult = $('#resourcesStarredListContainer').nestable('serialize');
    var itemWeight = 0;

    _.each( jsonNestableResult , function( e , i ){
      var element = that.resourcesStarred.get(e.id);
      element.set({ weight: itemWeight });
      itemWeight++;
    });

    that.saveChangesVisible(true);
  },



  removeResourceStarred: function( el ) {

    var that = this;

    var rId = parseInt($(el.currentTarget).attr('data-id'));
    var rs = that.resourcesStarred.get( rId );
    rs.set({deleted:1});
    that.updateList();
    that.saveChangesVisible(true);
   },

  assignResourceTerm: function() {
    var that = this;
    Backbone.history.navigate('starred/star/'+that.starredTerm.id+'/assign', {trigger:true});
  },

  saveResourceStarred: function() {
    var that = this;
    that.resourcesStarred.save(that.starredTerm.id);
    that.saveChangesVisible(false);
  },

  cancelResourceStarred: function() {
    var that = this;
    that.saveChangesVisible(false);
    that.initialize( that.starredTerm );
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
