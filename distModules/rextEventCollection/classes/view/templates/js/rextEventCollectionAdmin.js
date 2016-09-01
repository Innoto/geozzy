$(document).ready(function(){
  bindResourceForm();
});

function bindResourceForm(){
  $('select.cgmMForm-field-rExtEventCollection_events').multiList({
    itemActions : [
      { 'id': 'edit', 'html': '<i class="fa fa-pencil-square-o"></i>', 'action': editModalForm }
    ],
    placeholder: __('Select options')
  });
  $('#addEvents').on('click', function(){
    app.mainView.loadAjaxContentModal('/rtypeEvent/event/create', 'createEventModal', 'Create Event');
  });
}

function editModalForm(e){
  app.mainView.loadAjaxContentModal('/rtypeEvent/event/edit/'+e.value, 'editModalForm', 'Edit Event Collection');
}
