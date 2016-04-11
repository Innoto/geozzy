$(document).ready(function(){

  if($('.eventModal').size()>0){
    //bindModalEventForm();
    bindEventForm('.eventModal ');
  }
  else{
    bindEventForm('');
  }

  moveSubmitBtn('#createEventModal');
});


function bindEventForm(modal){
  // mostramos los valores guardados en bbdd
  var initDay = false;
  var endDay = false;

  if ($(modal+'input.cgmMForm-field-rextEvent_initDate').val() && $(modal+'input.cgmMForm-field-rextEvent_initDate').val() != ''){
    initDay = $(modal+'input.cgmMForm-field-rextEvent_initDate').val();
    //initDay = moment.unix(initDateTs_saved).utc();
  }
  if ($(modal+'input.cgmMForm-field-rextEvent_endDate').val() && $(modal+'input.cgmMForm-field-rextEvent_endDate').val() != ''){
    endDay = $(modal+'input.cgmMForm-field-rextEvent_endDate').val();
    //endDay = moment.unix(endDateTs_saved).utc();
  }

  // lanzamos los calendarios
  $(modal+'.initDate').datetimepicker({
    defaultDate:initDay
  });
  $(modal+'.endDate').datetimepicker({
    defaultDate:endDay
  });

  // recogemos los valores, los ponemos en UTC y los pasamos a timestamp antes de pasarlos al servidor
  $(modal+'.initDate').on('dp.change', function(e){
    e.date.tz(cogumelo.publicConf.date_timezone);
    initDateTs = e.date.unix();
    $(modal+'input.cgmMForm-field-rextEvent_initDate').val(initDateTs);
  });
  $(modal+'.endDate').on('dp.change', function(e){
    e.date.tz(cogumelo.publicConf.date_timezone);
    endDateTs = e.date.unix();
    $(modal+'input.cgmMForm-field-rextEvent_endDate').val(endDateTs);
  });

  // tuneamos los selectores
  $(modal+'select.cgmMForm-field-rextEvent_rextEventType').multiList({
    orientation: 'horizontal',
    icon: '<i class="fa fa-arrows"></i>'
  });

  $(modal+'select.cgmMForm-field-rextEvent_relatedResource').select2();
}


function moveSubmitBtn(query){
  var buttonsToMove = $(query).find('.gzzAdminToMove');

  if( buttonsToMove.size() > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone();
      cloneButtonBottom.appendTo( $(query+" .modal-footer") );
      $(this).hide();
    });
  }
}


function successResourceForm( data ){
  //resource
  $('#collEvents').append('<option data-image="'+data.image+'" selected="selected" value="'+data.id+'">'+data.title+'</option>');
  $('#createEventModal').modal('hide');
  $('#collEvents').trigger('change');


  //End resource
}
