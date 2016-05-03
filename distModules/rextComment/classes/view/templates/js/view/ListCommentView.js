
var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.ListCommentView = Backbone.View.extend({
/*
  commentFormTemplate : false,
  modalTemplate : false,
  idResource: false,
  commentType : false,
  */
  comments : false,
  events: {

  },

  initialize: function( idResource ) {

    var that = this;
    that.comments = new geozzy.commentComponents.CommentCollection([], { resource: idResource });
    that.comments.fetch(
      {
        success: function() {
          console.log(that.comments);
          that.updateList();
        }
      }
    );
  },

  render: function() {
/*
    var that = this;

    this.baseTemplate = _.template( $('#resourcesStarredList').html() );
    this.$el.html( this.baseTemplate(that.starredTerm.toJSON() ) );

    that.saveChangesVisible(false);
*/
  },

  updateList: function() {
/*
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
*/
  }

});
