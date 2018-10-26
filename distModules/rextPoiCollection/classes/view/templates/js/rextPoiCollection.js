$(document).ready(function(){
  bindResourceForm();
});

function bindResourceForm(){
  $('select.cgmMForm-field-rExtPoiCollection_pois').multiList({
    itemActions : [
      { 'id': 'edit', 'html': '<i class="far fa-edit"></i>', 'action': editModalForm }
    ],
    placeholder: __('Select options')
  });
  $('#addPois').on('click', function(){
    app.mainView.loadAjaxContentModal('/rtypePoi/poi/create', 'createPoiModal', { title: __('Create POI') } );
  });
}

function editModalForm(e){
  app.mainView.loadAjaxContentModal('/rtypePoi/poi/edit/'+e.value, 'editModalForm', { title: __('Edit POI Collection') } );
}
