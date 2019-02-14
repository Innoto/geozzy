
$( document ).ready( function() {

  $.fn.datetimepicker.Constructor.Default = $.extend( {}, $.fn.datetimepicker.Constructor.Default, {
    icons: {
        time: 'far fa-clock',
        date: 'fas fa-calendar',
        up: 'fas fa-arrow-up',
        down: 'fas fa-arrow-down',
        previous: 'fas fa-chevron-left',
        next: 'fas fa-chevron-right',
        today: 'far fa-calendar-check',
        clear: 'fas fa-trash-alt',
        close: 'fas fa-times'
    }
  } );

  if( $('.eventModal').length > 0 ) {
    rextEventJs.bindEventForm('.eventModal ');
  }
  else {
    rextEventJs.bindEventForm('');
  }

  rextEventJs.moveSubmitBtn('#createEventModal');
  rextEventJs.moveSubmitBtn('#editModalForm');
} );


var rextEventJs = {

  bindEventForm: function( modal ) {
    var that = this;

    // mostramos los valores guardados en bbdd
    var initDay = false;
    var endDay = false;

    if( $(modal+'input.cgmMForm-field-rextEvent_initDate').val() && $(modal+'input.cgmMForm-field-rextEvent_initDate').val() != '0000-00-00 00:00:00' && $(modal+'input.cgmMForm-field-rextEvent_initDate').val() != 'undefined' ) {
      initDay = $(modal+'input.cgmMForm-field-rextEvent_initDate').val();
      //initDay = moment.unix(initDateTs_saved).utc();
    }
    if( $(modal+'input.cgmMForm-field-rextEvent_endDate').val() && $(modal+'input.cgmMForm-field-rextEvent_endDate').val() != '0000-00-00 00:00:00' && $(modal+'input.cgmMForm-field-rextEvent_endDate').val() != 'undefined' ) {
      endDay = $(modal+'input.cgmMForm-field-rextEvent_endDate').val();
      //endDay = moment.unix(endDateTs_saved).utc();
    }

    // lanzamos los calendarios
    if( typeof(initDay)!='undefined' && initDay!=false ) {
      $(modal+'.initDate').datetimepicker( {
        defaultDate:initDay,
        locale: cogumelo.publicConf.langDefault
      } );
    }
    else {
      $(modal+'.initDate').datetimepicker({
        locale: cogumelo.publicConf.langDefault
      });
    }

    if( typeof(endDay)!='undefined' && endDay!=false ) {
      $(modal+'.endDate').datetimepicker( {
        defaultDate:endDay,
        locale: cogumelo.publicConf.langDefault
      } );
    }
    else {
      $(modal+'.endDate').datetimepicker({
        locale: cogumelo.publicConf.langDefault
      });
    }


    // recogemos los valores, los ponemos en UTC y los pasamos a timestamp antes de pasarlos al servidor
    $(modal+'.initDate').on( 'change.datetimepicker', function( e ) {
      if( e.date ) {
        e.date.tz(cogumelo.publicConf.date_timezone.project);
        initDateTs = e.date.format();
      }
      else {
        initDateTs = false;
      }
      // console.log(initDateTs);

      $(modal+'input.cgmMForm-field-rextEvent_initDate').val(initDateTs);
    } );
    $(modal+'.endDate').on( 'change.datetimepicker', function( e ) {
      if( e.date ) {
        e.date.tz(cogumelo.publicConf.date_timezone.project);
        endDateTs = e.date.format();
      }
      else {
        endDateTs = false;
      }
      $(modal+'input.cgmMForm-field-rextEvent_endDate').val(endDateTs);
    } );

    // tuneamos los selectores
    $(modal+'select.cgmMForm-field-rextEvent_rextEventType').multiList( {
      orientation: 'horizontal',
      icon: '<i class="fas fa-arrows-alt"></i>'
    } );

    $(modal+'select.cgmMForm-field-rextEvent_relatedResource').select2();
  },

  moveSubmitBtn: function( query ) {
    var that = this;
    var buttonsToMove = $(query).find('.gzzAdminToMove');

    if( buttonsToMove.length > 0 ) {
      buttonsToMove.each( function() {
        var that = this;
        var cloneButtonBottom = $(this).clone(true, true);
        cloneButtonBottom.appendTo( $(query+" .modal-footer") );
        $(this).hide();
      } );
    }
  },

  successResourceForm: function( data ) {
    var that = this;
    if( $('#collEvents option[value='+data.id+']').length > 0 ) {
      $('#collEvents option[value='+data.id+']').text(data.title);
      $('#editModalForm').modal('hide');
    }
    else {
      $('#collEvents').append('<option data-image="'+data.image+'" selected="selected" value="'+data.id+'">'+data.title+'</option>');
      $('#createEventModal').modal('hide');
    }
    $('#collEvents').trigger('change');
  }

};
