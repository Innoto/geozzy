$(document).ready(function(){
  initResource();
});

function initResource(){
  //showResource();
  $('.accordion-heading').bind('click', function(){

    changeArrow(this);
  });
}

function changeArrow(elem){
  $(elem).find('i').toggle();
}
