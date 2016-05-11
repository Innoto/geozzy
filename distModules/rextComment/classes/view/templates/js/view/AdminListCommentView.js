var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.AdminListCommentView = Backbone.View.extend({

  el : false,
  comments : false,
  commentType : false,
  adminListCommentTemplate : false,
  adminListCommentItemTemplate : false,
  adminListSuggestionItemTemplate : false,

  events: {
    "change .typeComment": "changeCType"
  },

  initialize: function( idResource ) {
    var that = this;
    that.$el = $("#wrapper .rextComment");
    that.delegateEvents();
    that.commentType = that.$el.find('.typeComment').val();


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
    var commentsFiltered =  false;

    that.$el.find(".commentListContainer").html('');
    that.adminListCommentTemplate = _.template( geozzy.commentComponents.adminListCommentTemplate );
    that.adminListCommentItemTemplate = _.template( geozzy.commentComponents.adminListCommentItemTemplate );
    that.adminListSuggestionItemTemplate = _.template( geozzy.commentComponents.adminListSuggestionItemTemplate );
    commentsFiltered = that.comments.search({type : parseInt(that.commentType) });
    _.each( commentsFiltered.toJSON() , function(item){
      data = {
        commentContent: item.content,
        commentRate: item.rate,
        commentUserName: false,
        commentTimeCreation: item.timeCreation,
        commentPublished: item.published
      }
      if(item.userName){
        data.commentUserName = item.userName;
        data.commentUserEmail = item.userEmail;
        data.commentUserVerified = item.userVerified;
      }else{
        data.commentUserName = item.anonymousName;
        data.commentUserEmail = item.anonymousEmail;
        data.commentUserVerified = false;
      }
      commentsItems += that.adminListCommentItemTemplate(data);
    });
    that.$el.find('.commentListContainer').append( that.adminListCommentTemplate({ comments:commentsItems }) );
  },

  changeCType: function() {
    var that = this;
    that.commentType = that.$el.find('.typeComment').val();
    that.render();
  }

});
