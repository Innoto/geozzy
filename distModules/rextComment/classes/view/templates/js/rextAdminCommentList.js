$(document).ready(function(){
  var idResource = resourceViewData.id;
  if(idResource){
    geozzy.commentInstance.adminListComment(idResource);
  }
});
