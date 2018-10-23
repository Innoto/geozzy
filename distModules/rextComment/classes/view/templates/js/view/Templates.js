var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};


geozzy.commentComponents.modalMdTemplate = ''+
'<div id="<%- modalId %>" class="modal fade" tabindex="-1" role="dialog">'+
  '<div class="modal-dialog modal-md">'+
    '<div class="modal-content">'+
      '<div class="modal-header">'+
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '<img class="iconModal img-responsive" src="'+cogumelo.publicConf.media+'/img/iconModal.png"></img>'+
        //'<h3 class="modal-title"><%- modalTitle %></h3>'+
      '</div>'+
      '<div class="modal-body"></div>'+
    '</div>'+
  '</div>'+
'</div>';

geozzy.commentComponents.commentFormTemplate = ''+
'<div class="commentFormModal"></div>';

geozzy.commentComponents.commentFormOkTemplate = ''+
'<h3>'+__("Your contribution is welcome")+'</h3>';

geozzy.commentComponents.listCommentTemplate = ''+
'<div class="commentListContainer">'+
  '<div class="commentList"><%= comments %></div>'+
  '<% if(commentsToShow){ %> <div class="commentShowMore">'+__("See all comments")+'<i class="fas fa-caret-down" aria-hidden="true"></i></div> <% } %>'+
'</div>';

geozzy.commentComponents.listCommentItemTemplate = ''+
'<div class="commentItem">'+
  '<div class="commentContent"><%= commentContent %></div>'+
  '<% if(commentRate !== null ){ %>'+
  '<div class="commentRate">'+
    '<% for (var i = 0; i < 5; i++) { '+
      'if(commentRate > i){'+
        '%><i class="fas fa-star" aria-hidden="true"></i><%'+
      '}else{'+
        '%><i class="far fa-star" aria-hidden="true"></i><%'+
      '}'+
    '}  %>'+
  '</div>'+
  '<% } %>'+
  '<div class="commentInfo"><a class="userName" <% if(typeof commentUserId !== "undefined"){ %>data-user-id="<%- commentUserId %>"<% } %>><%- commentUserName %></a>  <%- commentTimeCreation %> </div>'+
'</div>';

geozzy.commentComponents.adminListCommentTemplate = ''+
'<div class="commentList"><%= comments %></div>';

geozzy.commentComponents.adminListCommentItemTemplate = ''+
'<div class="commentItem" data-rextcomment-id="<%- commentId %>">'+
  '<div class="row">'+
    '<div class="col-md-8">'+
      '<div class="commentTimeCreation"><%- commentTimeCreation %></div>'+
      '<div class="commentUserInfo">'+
        '<%- commentUserName %> ( <%- commentUserEmail %> )'+
        '<% if(commentUserVerified  === "1"){'+
          '%><span class="userVerified"><i class="fas fa-check" aria-hidden="true"></i></span><%'+
        '} %>'+
      '</div>'+
    '</div>'+
    '<div class="col-md-4">'+
      '<ul class="commentOptions clearfix pull-right">'+
        '<li>'+
          '<label><input type="checkbox" class="commentPublished switchery" <% if(commentPublished === 1){'+
            '%>checked<%'+
          '} %>'+
          ' value="1" name="commentPublished"><span class="labelText">'+__("Published")+'</span></label>'+
        '</li>'+
        '<li><button class="btn btn-default deleteComment"><i class="fas fa-times" aria-hidden="true"></i></button></li>'+
      '</ul>'+
    '</div>'+
    '<div class="col-md-12"><span class="commentTypeLabel">'+__("Comment")+'</span>'+
      '<% if(commentRate !== null){ %>'+
      '<span class="commentStars">'+
      '<% for (var i = 0; i < 5; i++) { '+
        'if(commentRate > i){'+
          '%><i class="fas fa-star" aria-hidden="true"></i><%'+
        '}else{'+
          '%><i class="far fa-star" aria-hidden="true"></i><%'+
        '}'+
      '}  %> </span>'+
      '<% } %>'+
    '</div>'+
    '<div class="col-md-12 commentContent"><%= commentContent %></div>'+
  '</div>'+
'</div>';

geozzy.commentComponents.adminListSuggestionItemTemplate = ''+
'<div class="commentItem" data-rextcomment-id="<%- commentId %>">'+
  '<div class="row">'+
    '<div class="col-md-8">'+
      '<div class="commentTimeCreation"><%- commentTimeCreation %></div>'+
      '<div class="commentUserInfo">'+
        '<%- commentUserName %> ( <%- commentUserEmail %> )'+
        '<% if(commentUserVerified === "1"){'+
          '%><span class="userVerified"><i class="fas fa-check" aria-hidden="true"></i></span><%'+
        '} %>'+
      '</div>'+
    '</div>'+
    '<div class="col-md-4">'+
      '<ul class="suggestOptions clearfix pull-right">'+
        '<li><button class="btn btn-primary greatSuggest <% if(commentStatusIdName == "commentValidated"){ %> selected <% } %>"><i class="far fa-thumbs-up" aria-hidden="true"></i></button></li>'+
        '<li><button class="btn btn-danger irrelevantSuggest <% if(commentStatusIdName == "commentDenied"){ %> selected <% } %>"><i class="far fa-thumbs-down" aria-hidden="true"></i></button></li>'+
        '<li><button class="btn btn-default deleteComment"><i class="fas fa-times" aria-hidden="true"></i></button></li>'+
      '</ul>'+
    '</div>'+
    '<div class="col-md-12"><span class="commentTypeLabel"><%- suggestTypeName %></span></div>'+
    '<div class="col-md-12 commentContent"><%- commentContent %></div>'+
  '</div>'+

'</div>';
