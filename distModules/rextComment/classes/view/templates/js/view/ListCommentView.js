var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.ListCommentView = Backbone.View.extend({

  el : ".rExtCommentList",
  tagName : '',
  comments : false,
  listCommentTemplate : false,
  listCommentItemTemplate : false,
  commentsToShow : 3,
  commentAppParent : false,

  events: {
    "click .commentShowMore": "showAll",
    "click .userName": "userNameEvent"
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
    if( typeof cogumelo.publicConf.C_LANG === 'string' ) {
      moment.locale(cogumelo.publicConf.C_LANG);
    }

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
        commentUserName: false,
        commentTimeCreation: moment(item.timeCreation).format('LLL')
      };
      if( item.rate !== 0){
        data.commentRate= item.rate/20;
      }else{
        data.commentRate= null;
      }
      if(item.userName){
        data.commentUserName = item.userName;
        data.commentUserId = item.user;
        if(item.userAvatarId){
          data.commentUserAvatarId = item.userAvatarId;
          data.commentUserAvatarName = item.userAvatarName;
          data.commentUserAvatarAKey = item.userAvatarAKey;
        }
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
  },
  userNameEvent : function (e){
    var that = this;
    var idUser = $(e.target).attr('data-user-id');
    if(idUser){
      that.commentAppParent.userCallBack(idUser);
    }
  }

});
