$(document).ready(function(){
  if(commentTermID && suggestTermID){
    initCtype([ {id: commentTermID ,show: ["rate"]}, {id: suggestTermID, show: ["suggestType"]} ]);
  }

  $("input.inputRating").rating({
    min: 0,
    max: 5,
    step: 1,
    size: 'xs',
    theme: 'krajee-fa',
    clearCaption: '',
    clearButton: '',
    defaultCaption: '',
    starCaptions: {
      1: '',
      2: '',
      3: '',
      4: '',
      5: ''
    },
    filledStar:'<i class="fas fa-star" aria-hidden="true"></i>',
    emptyStar:'<i class="far fa-star" aria-hidden="true"></i>'
  });

});

function initCtype( param ){
  hideFields(param);
  showFields(searchCtypeData(param, $('input.cgmMForm-field-type[name=type]:checked').val()));
  $('input.cgmMForm-field-type').change(function(){
    hideFields(param);
    showFields(searchCtypeData(param, $('input.cgmMForm-field-type[name=type]:checked').val()));
  });
}

function searchCtypeData( param , id ){
  return $.grep(param, function(e){
    return e.id == id;
  })[0];
}

function hideFields( param ){
  console.log(param);
  $.each(param, function(index, item) {
    $.each(item.show, function(i, e) {
      $('.cgmMForm-wrap.cgmMForm-field-'+e).hide();
    });
  });
}
function showFields(param){
  $.each(param.show, function(index, item) {
    $('.cgmMForm-wrap.cgmMForm-field-'+item).show();
  });
}
