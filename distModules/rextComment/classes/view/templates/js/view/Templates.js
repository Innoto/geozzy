var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};


geozzy.commentComponents.modalMdTemplate = ''+
'<div id="<%- modalId %>" class="modal fade" tabindex="-1" role="dialog">'+
  '<div class="modal-dialog modal-md">'+
    '<div class="modal-content">'+
      '<div class="modal-header">'+
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '<h3 class="modal-title"><%- modalTitle %></h3>'+
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
  '<% if(commentsToShow){ %> <div class="commentShowMore">'+__("See all comments")+'</div> <% } %>'+
'</div>';

geozzy.commentComponents.listCommentItemTemplate = ''+
'<div class="commentItem">'+
  '<div class="commentContent"><%- commentContent %></div>'+
  '<div class="commentRate">'+
    '<% for (var i = 0; i < 5; i++) { '+
      'if(commentRate > i){'+
        '%><i class="fa fa-star" aria-hidden="true"></i><%'+
      '}else{'+
        '%><i class="fa fa-star-o" aria-hidden="true"></i><%'+
      '}'+
    '}  %>'+
  '</div>'+
  '<div class="commentInfo">'+__("by")+' <span><%- commentUserName %></span>  <%- commentTimeCreation %></div>'+
'</div>';

geozzy.commentComponents.adminListCommentTemplate = ''+
'<div class="commentList"><%= comments %></div>';

geozzy.commentComponents.adminListCommentItemTemplate = ''+
'<div class="commentItem">'+
  '<div class="row">'+
    '<div class="col-md-8">'+
      '<div class="commentTimeCreation"><%- commentTimeCreation %></div>'+
      '<div class="commentUserInfo">'+
        '<%- commentUserName %> ( <%- commentUserEmail %> )'+
        '<% if(commentUserVerified === true){'+
          '%><i class="fa fa-check" aria-hidden="true"></i><%'+
        '} %>'+
      '</div>'+
    '</div>'+
    '<div class="col-md-4">'+
      '<ul class="clearfix pull-right">'+
        '<li>'+
          '<label><input type="checkbox" class="commentPublished switchery" checked="<% if(commentPublished === true){'+
            '%>checked<%'+
          '}else{'+
            '%>false<%'+
          '} %>'+
          '" value="1" name="commentPublished"><span class="labelText">'+__("Comment")+'</span></label>'+
        '</li>'+
        '<li><button class="btn deleteComment"><i class="fa fa-times" aria-hidden="true"></i></button></li>'+
      '</ul>'+
    '</div>'+
    '<div class="col-md-12">'+__("Comment")+
      '<% for (var i = 0; i < 5; i++) { '+
        'if(commentRate > i){'+
          '%><i class="fa fa-star" aria-hidden="true"></i><%'+
        '}else{'+
          '%><i class="fa fa-star-o" aria-hidden="true"></i><%'+
        '}'+
      '}  %>'+
    '</div>'+
  '</div>'+
  '<div class="col-md-12 commentContent"><%- commentContent %></div>'+
'</div>';

geozzy.commentComponents.adminListSuggestionItemTemplate = ''+
'<div class="commentItem">'+
  '<div class="row">'+
    '<div class="col-md-8">'+
      '<div class="commentTimeCreation"><%- commentTimeCreation %></div>'+
      '<div class="commentUserInfo">'+
        '<%- commentUserName %> ( <%- commentUserEmail %> )'+
        '<% if(commentUserVerified === true){'+
          '%><i class="fa fa-check" aria-hidden="true"></i><%'+
        '} %>'+
      '</div>'+
    '</div>'+
    '<div class="col-md-4">'+
      '<ul class="clearfix pull-right">'+
        '<li>'+
          '<label><input type="checkbox" class="commentPublished switchery" checked="<% if(commentPublished === true){'+
            '%>checked<%'+
          '}else{'+
            '%>false<%'+
          '} %>'+
          '" value="1" name="commentPublished"><span class="labelText">'+__("Comment")+'</span></label>'+
        '</li>'+
        '<li><button class="btn deleteComment"><i class="fa fa-times" aria-hidden="true"></i></button></li>'+
      '</ul>'+
    '</div>'+
    '<div class="col-md-12"><%- suggestTypeName %></div>'+
  '</div>'+
  '<div class="col-md-12 commentContent"><%- commentContent %></div>'+
'</div>';
