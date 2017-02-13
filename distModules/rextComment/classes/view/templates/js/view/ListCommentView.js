var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.ListCommentView = Backbone.View.extend({

  el : $(".rExtCommentList"),
  tagName : '',
  comments : false,
  listCommentTemplate : false,
  listCommentItemTemplate : false,
  commentsToShow : 3,

  events: {
    "click .commentShowMore": "showAll"
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
    var commentsItems = '';
    that.$el.html('');
    that.listCommentTemplate = _.template( geozzy.commentComponents.listCommentTemplate );
    that.listCommentItemTemplate = _.template( geozzy.commentComponents.listCommentItemTemplate );
    var commentNumber = 0;

    if( that.comments.length <= that.commentsToShow) {
      that.commentsToShow = false;
    }

    _.each( that.comments.toJSON() , function(item){
      if(that.commentsToShow !== false && that.commentsToShow === commentNumber){
        return false;
      }
      commentNumber++;
      data = {
        commentId: item.id,
        commentContent: item.content.replace(/\n/g, '<br>\n'),
        commentRate: item.rate/20,
        commentUserName: false,
        commentTimeCreation: item.timeCreation
      }
      if(item.userName){
        data.commentUserName = item.userName;
      }else{
        data.commentUserName = item.anonymousName;
      }
      commentsItems += that.listCommentItemTemplate(data);
    });
    that.$el.append( that.listCommentTemplate({ comments:commentsItems, commentsToShow:that.commentsToShow }) );

  },
  showAll : function (){
    var that = this;
    that.commentsToShow = false;
    that.render();
  }

});
