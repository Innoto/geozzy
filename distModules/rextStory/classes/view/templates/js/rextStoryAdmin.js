$(document).ready(function(){
  bindResourceForm();
});

function bindResourceForm(){
  $('select.cgmMForm-field-rExtStory_steps').multiList({
    itemActions : [],
    placeholder: __('Select options')
  });
}

function editModalForm(e){
  app.mainView.loadAjaxContentModal('/rtypeEvent/event/edit/'+e.value, 'editModalForm', 'Edit Event Collection');
}
