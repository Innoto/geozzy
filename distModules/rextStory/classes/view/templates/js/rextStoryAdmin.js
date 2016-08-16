$(document).ready(function(){
  bindResourceForm();
});

function bindResourceForm(){
  $('select.cgmMForm-field-rExtStory_steps').multiList({
    itemActions : [],
  });
}

function editModalForm(e){
  app.mainView.loadAjaxContentModal('/rtypeEvent/event/edit/'+e.value, 'editModalForm', 'Edit Event Collection');
}
