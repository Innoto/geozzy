$(document).ready(function(){
  bindResourceForm();
});

function bindResourceForm(){
  $('select.cgmMForm-field-rExtStory_steps').multiList({
    itemActions : [],
    placeholder: __('Select options')
  });
}
