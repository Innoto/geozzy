$porto = false;



$(document).ready(function(){

  if($('.eventModal').length >0){
    bindEventForm('.eventModal ');
  }
  else{
    bindEventForm('');
  }

  moveSubmitBtn('#createEventModal');
  moveSubmitBtn('#editModalForm');
});



function bindEventForm(modal){
  // mostramos los valores guardados en bbdd
  var initDay = false;
  var endDay = false;

  if ($(modal+'input.cgmMForm-field-rextEvent_initDate').val() && $(modal+'input.cgmMForm-field-rextEvent_initDate').val() != '0000-00-00 00:00:00' && $(modal+'input.cgmMForm-field-rextEvent_initDate').val() != 'undefined'){
    initDay = $(modal+'input.cgmMForm-field-rextEvent_initDate').val();
    //initDay = moment.unix(initDateTs_saved).utc();
  }
  if ($(modal+'input.cgmMForm-field-rextEvent_endDate').val() && $(modal+'input.cgmMForm-field-rextEvent_endDate').val() != '0000-00-00 00:00:00' && $(modal+'input.cgmMForm-field-rextEvent_endDate').val() != 'undefined'){
    endDay = $(modal+'input.cgmMForm-field-rextEvent_endDate').val();
    //endDay = moment.unix(endDateTs_saved).utc();
  }

  // lanzamos los calendarios
  if(typeof(initDay)!='undefined' && initDay!=false){
    $(modal+'.initDate').datetimepicker({
      defaultDate:initDay
    });
  }
  else{
    $(modal+'.initDate').datetimepicker();
  }

  if(typeof(endDay)!='undefined' && endDay!=false){
    $(modal+'.endDate').datetimepicker({
      defaultDate:endDay
    });
  }
  else{
    $(modal+'.endDate').datetimepicker();
  }


  // recogemos los valores, los ponemos en UTC y los pasamos a timestamp antes de pasarlos al servidor
  $(modal+'.initDate').on('dp.change', function(e){
    if( e.date){
      e.date.tz(cogumelo.publicConf.date_timezone.project);
      initDateTs = e.date.unix();
    }
    else{
      initDateTs = false;
    }
    $(modal+'input.cgmMForm-field-rextEvent_initDate').val(initDateTs);
  });
  $(modal+'.endDate').on('dp.change', function(e){
    if( e.date){
      e.date.tz(cogumelo.publicConf.date_timezone.project);
      endDateTs = e.date.unix();
    }
    else{
      endDateTs = false;
    }
    $(modal+'input.cgmMForm-field-rextEvent_endDate').val(endDateTs);
  });

  // tuneamos los selectores
  $(modal+'select.cgmMForm-field-rextEvent_rextEventType').multiList({
    orientation: 'horizontal',
    icon: '<i class="fas fa-arrows-alt"></i>'
  });

  $(modal+'select.cgmMForm-field-rextEvent_relatedResource').select2();
}


function moveSubmitBtn(query){
  var buttonsToMove = $(query).find('.gzzAdminToMove');

  if( buttonsToMove.length > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone(true, true);
      cloneButtonBottom.appendTo( $(query+" .modal-footer") );
      $(this).hide();
    });
  }
}


function successResourceForm( data ){

  if( $('#collEvents option[value='+data.id+']').length > 0 ){
    $('#collEvents option[value='+data.id+']').text(data.title);
    $('#editModalForm').modal('hide');
  }else{
    $('#collEvents').append('<option data-image="'+data.image+'" selected="selected" value="'+data.id+'">'+data.title+'</option>');
    $('#createEventModal').modal('hide');
  }
  $('#collEvents').trigger('change');
}
