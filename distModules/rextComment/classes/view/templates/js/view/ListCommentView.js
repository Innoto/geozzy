var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.ListCommentView = Backbone.View.extend({

  el : $(".commentSec .rExtCommentList"),
  tagName : '',
  comments : false,
  listCommentItemTemplate : false,
  events: {

  },

  initialize: function( idResource ) {

    var that = this;
    that.comments = new geozzy.commentComponents.CommentCollection([], { resource: idResource });
    that.comments.fetch({
      success: function() {
        that.render();
      }
    });
  },

  render: function() {

    var that = this;

    that.listCommentItemTemplate = _.template( geozzy.commentComponents.listCommentItemTemplate );
    this.$el.html('');
    _.each( that.comments.toJSON() , function(item){
      data = {
        commentContent: item.content,
        commentRate: item.rate,
        commentUserName: false,
        commentTimeCreation: item.timeCreation
      }
      if(item.userName){
        data.commentUserName = item.userName;
      }else{
        data.commentUserName = item.anonymousName;
      }

      that.$el.append( that.listCommentItemTemplate(data) );
    });

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
