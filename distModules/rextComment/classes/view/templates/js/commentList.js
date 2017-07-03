$(document).ready(function(){
  console.log($(".rExtCommentList"));
  console.log($(".CommentList.js"));
  var idResource = $('.resource').attr('data-resource');
  if(idResource){
    geozzy.commentInstance.listComment(idResource);
  }
});
