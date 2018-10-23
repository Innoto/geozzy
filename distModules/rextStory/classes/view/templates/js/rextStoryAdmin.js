$(document).ready(function(){
  bindResourceForm();
});

function bindResourceForm(){
  alert('1');
  $('select.cgmMForm-field-rExtStory_steps').multiList({
    itemActions : [],
    placeholder: __('Select options')
  });
}
