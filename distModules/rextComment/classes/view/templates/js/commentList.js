$(document).ready(function(){
  var idResource = $('.resource').attr('data-resource');
  if(idResource){
    geozzy.commentInstance.listComment(idResource);
  }
});
