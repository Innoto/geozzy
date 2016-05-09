var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.AdminListCommentView = Backbone.View.extend({

  el : false,
  comments : false,
  adminListCommentTemplate : false,
  adminListCommentItemTemplate : false,
  adminListSuggestionItemTemplate : false,

  events: {
    //"click .commentShowMore": "showAll"
  },

  initialize: function( idResource ) {
    var that = this;
    that.$el = $("#wrapper .rextComment");
    that.delegateEvents();
    that.comments = new geozzy.commentComponents.CommentSuggestionCollection([], { resource: idResource });
    that.comments.fetch({
      success: function() {
        that.render();
      }
    });
  },

  render: function() {

    var that = this;
    var commentsItems = '';
    that.$el.find(".commentListContainer").html('');
    that.adminListCommentTemplate = _.template( geozzy.commentComponents.adminListCommentTemplate );
    that.adminListCommentItemTemplate = _.template( geozzy.commentComponents.adminListCommentItemTemplate );
    that.adminListSuggestionItemTemplate = _.template( geozzy.commentComponents.adminListSuggestionItemTemplate );

    _.each( that.comments.toJSON() , function(item){

      data = {
        commentContent: item.content,
        commentRate: item.rate,
        commentUserName: false,
        commentTimeCreation: item.timeCreation
      }
      if(item.userName){
        data.commentUserName = item.userName;
        data.commentUserEmail = item.userEmail;
      }else{
        data.commentUserName = item.anonymousName;
        data.commentUserEmail = item.anonymousEmail;
      }
      commentsItems += that.adminListCommentItemTemplate(data);
    });
    that.$el.find('.commentListContainer').append( that.adminListCommentTemplate({ comments:commentsItems }) );
  }

});
