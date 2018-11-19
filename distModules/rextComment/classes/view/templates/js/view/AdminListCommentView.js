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
    "change .typeComment": "changeCType",
    "click .commentItem .deleteComment": "deleteComment",
    "click .commentItem .commentPublished": "commentPublished",
    "click .commentItem .greatSuggest": "greatSuggest",
    "click .commentItem .irrelevantSuggest": "irrelevantSuggest"
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
        commentId: item.id,
        commentContent: item.content.replace(/\n/g, '<br>\n'),
        commentUserName: false,
        commentTimeCreation: item.timeCreation,
        commentStatusIdName: item.statusIdName,
        commentPublished: item.published
      };
      if( item.rate !== null){
        data.commentRate = item.rate/20;
      }else{
        data.commentRate= null;
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
      if(item.typeIdName === 'comment'){
        commentsItems += that.adminListCommentItemTemplate(data);
      }else if(item.typeIdName === 'suggest'){
        data.suggestTypeName = item.suggestTypeName;
        commentsItems += that.adminListSuggestionItemTemplate(data);
      }

    });

    that.$el.find('.commentListContainer').append( that.adminListCommentTemplate({ comments:commentsItems }) );
    var els = that.$el.find('.commentPublished.switchery');
    els.each(function( index )  {
      var switchery = new Switchery( this, { color : '#58ba81', secondaryColor : '#FF6A4B'} );
    });
  },

  changeCType: function() {
    var that = this;
    that.commentType = that.$el.find('.typeComment').val();
    that.render();
  },

  deleteComment: function( e ){
    var that = this;
    var r = confirm(__("Are you sure you want to delete this comment?"));
    if (r == true) {
      var commentID = $(e.target).closest('.commentItem').attr('data-rextcomment-id');
      var comment = false;
      if( commentID ){
        that.comments.editableUrl();
        var comment = that.comments.get(commentID);
        comment.destroy({success: function(model, response) {
          that.render();
        }});
      }
    }
  },
  commentPublished: function( e ){
    var that = this;

    var commentID = $(e.target).closest('.commentItem').attr('data-rextcomment-id');
    that.comments.editableUrl();
    var comment = that.comments.get(commentID);
    if( $(e.target).is(':checked') ){
      comment.set("published",'1');
    }else{
      comment.set("published",'0');
    }
    comment.save();

  },
  greatSuggest: function( e ){
    var that = this;
    var r = confirm(__("Are you sure you want to try this suggestion?"));
    if (r == true) {
      var commentID = $(e.currentTarget).closest('.commentItem').attr('data-rextcomment-id');

      $(e.currentTarget).closest('.suggestOptions').find('button').removeClass('selected');
      $(e.currentTarget).addClass('selected');

      that.comments.editableUrl();
      var comment = that.comments.get(commentID);
      comment.set("statusIdName",'commentValidated');

      comment.save();
    }
  },
  irrelevantSuggest: function( e ){
    var that = this;
    var r = confirm(__("Are you sure you want to mark as irrelevant this suggestion?"));
    if (r == true) {
      var commentID = $(e.currentTarget).closest('.commentItem').attr('data-rextcomment-id');

      $(e.currentTarget).closest('.suggestOptions').find('button').removeClass('selected');
      $(e.currentTarget).addClass('selected');

      that.comments.editableUrl();
      var comment = that.comments.get(commentID);
      comment.set("statusIdName",'commentDenied');

      comment.save();
    }
  }
});
