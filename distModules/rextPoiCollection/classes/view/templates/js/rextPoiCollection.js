$(document).ready(function(){
  bindResourceForm();
  initializeMap(poiFormId);
});

function bindResourceForm(){
  $('select.cgmMForm-field-rExtPoiCollection_pois').multiList({
    itemActions : [
      { 'id': 'edit', 'html': '<i class="fa fa-pencil-square-o"></i>', 'action': editModalForm }
    ],
  });
  $('#addPois').on('click', function(){
    app.mainView.loadAjaxContentModal('/rtypePoi/poi/create', 'createPoiModal', 'Create POI');
  });
}

function editModalForm(e){
  app.mainView.loadAjaxContentModal('/rtypePoi/poi/edit/'+e.value, 'editModalForm', 'Edit POI Collection');
}
