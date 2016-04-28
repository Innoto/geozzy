$(document).ready(function(){
  //hideFields();
  //showFields($('input.cgmMForm-field-type').val());
/*
  $('input.cgmMForm-field-type').change(function(){
    hideFields();
    showFields($('input.cgmMForm-field-type').val());
  });
*/
  function hideFields(){
    $('div.cgmMForm-field-rate').hide();
    $('div.cgmMForm-field-suggestType').hide();
  }
  function showFields(param){
    if(param){
      if(param === "125"){
        $('div.cgmMForm-field-rate').show();
      }
      if(param === "126"){
        $('div.cgmMForm-field-suggestType').show();
      }
    }
  }
});
