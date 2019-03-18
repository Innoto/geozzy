
$( document ).ready( function() {

  rextEventJs.bindStylesSelectors();

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

  options: {
    modal: '',
    initDate: {
      first: false,
      second: false,
      timeFirst: false,
      timeSecond: false
    },
    endDate: {
      first: false,
      second: false,
      timeFirst: false,
      timeSecond: false
    }
  },

  bindStylesSelectors: function() {
    // cogumelo.log('function: bindStylesSelectors');
    var that = this;

    // Tuneamos los selectores
    $( that.options.modal + 'select.cgmMForm-field-rextEvent_rextEventType' ).multiList( {
      orientation: 'horizontal',
      icon: '<i class="fas fa-arrows-alt"></i>'
    } );
    $( that.options.modal + 'select.cgmMForm-field-rextEvent_relatedResource' ).select2();
  },
  bindEventForm: function( modal ) {
    // cogumelo.log('function: bindEventForm');
    var that = this;

    that.options.modal = modal;

    that.getDates();
    that.loadDateTimePicker();
    that.checkboxDateRange_onchange();
    that.selectTime_onchange();
    that.setFunctionsDates();
  },

  loadDateTimePicker: function() {
    // cogumelo.log('function: loadDateTimePicker');
    var that = this;

    // LANZAMOS LOS CALENDARIOS
    // Fecha initDate
    if( typeof( that.options.initDate.first ) != 'undefined' && that.options.initDate.first != false ) {
      $( that.options.modal + '.initDateFirst' ).datetimepicker( {
        defaultDate: that.options.initDate.first,
        locale: cogumelo.publicConf.langDefault,
        format: 'L'
      } );
    }
    else {
      $( that.options.modal + '.initDateFirst' ).datetimepicker( {
        locale: cogumelo.publicConf.langDefault,
        format: 'L'
      } );
    }
    // Hora initTimeFirst (lo pillamos de initDateFirst)
    var optionSelectedInitTime = $('select.cgmMForm-field-rextEvent_selectInitTime option:selected').val();
    if( typeof( that.options.initDate.first ) != 'undefined' && that.options.initDate.first != false ) {
      var initTimeFirstDefault = ( optionSelectedInitTime !== 'notTime' ) ? that.options.initDate.first : '';
      $( that.options.modal + '.initTimeFirst' ).datetimepicker( {
        defaultDate: initTimeFirstDefault,
        locale: cogumelo.publicConf.langDefault,
        format: 'LT'
      } );
    }
    else {
      $( that.options.modal + '.initTimeFirst' ).datetimepicker( {
        locale: cogumelo.publicConf.langDefault,
        format: 'LT'
      } );
    }
    // Hora initTimeSecond (lo pillamos de initDateSecond)
    if( typeof( that.options.initDate.second ) != 'undefined' && that.options.initDate.second != false ) {
      var initTimeSecondDefault = ( optionSelectedInitTime === 'rangeTime' ) ? that.options.initDate.second : '';
      $( that.options.modal + '.initTimeSecond' ).datetimepicker( {
        defaultDate: initTimeSecondDefault,
        locale: cogumelo.publicConf.langDefault,
        format: 'LT'
      } );
    }
    else {
      $( that.options.modal + '.initTimeSecond' ).datetimepicker( {
        locale: cogumelo.publicConf.langDefault,
        format: 'LT'
      } );
    }


    // Fecha endDate
    if( typeof( that.options.endDate.first ) != 'undefined' && that.options.endDate.first != false ) {
      $( that.options.modal + '.endDateFirst' ).datetimepicker( {
        defaultDate: that.options.endDate.first,
        locale: cogumelo.publicConf.langDefault,
        format: 'L'
      } );
    }
    else {
      $( that.options.modal + '.endDateFirst' ).datetimepicker( {
        locale: cogumelo.publicConf.langDefault,
        format: 'L'
      } );
    }
    // Hora endTimeFirst (lo pillamos de endDateFirst)
    var optionSelectedEndTime = $('select.cgmMForm-field-rextEvent_selectEndTime option:selected').val();
    if( typeof( that.options.endDate.first ) != 'undefined' && that.options.endDate.first != false ) {
      var endTimeFirstDefault = ( optionSelectedEndTime !== 'notTime' ) ? that.options.endDate.first : '';
      $( that.options.modal + '.endTimeFirst' ).datetimepicker( {
        defaultDate: endTimeFirstDefault,
        locale: cogumelo.publicConf.langDefault,
        format: 'LT'
      } );
    }
    else {
      $( that.options.modal + '.endTimeFirst' ).datetimepicker( {
        locale: cogumelo.publicConf.langDefault,
        format: 'LT'
      } );
    }
    // Hora endTimeSecond (lo pillamos de endDateSecond)
    if( typeof( that.options.endDate.second ) != 'undefined' && that.options.endDate.second != false ) {
      var endTimeSecondDefault = ( optionSelectedEndTime === 'rangeTime' ) ? that.options.endDate.second : '';
      $( that.options.modal + '.endTimeSecond' ).datetimepicker( {
        defaultDate: endTimeSecondDefault,
        locale: cogumelo.publicConf.langDefault,
        format: 'LT'
      } );
    }
    else {
      $( that.options.modal + '.endTimeSecond' ).datetimepicker( {
        locale: cogumelo.publicConf.langDefault,
        format: 'LT'
      } );
    }
  },
  getDates: function() {
    // cogumelo.log('function: getDates');
    var that = this;

    if( $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateFirst').val() &&
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateFirst').val() != '0000-00-00 00:00:00' &&
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateFirst').val() != 'undefined' ) {
      that.options.initDate.first = $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateFirst' ).val();
      // cogumelo.log('initDateFirst', that.options.initDate.first);
    }
    if( $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateSecond').val() &&
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateSecond').val() != '0000-00-00 00:00:00' &&
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateSecond').val() != 'undefined' ) {
      that.options.initDate.second = $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateSecond' ).val();
      // cogumelo.log('initDateSecond', that.options.initDate.second);
    }

    if( $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateFirst').val() &&
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateFirst').val() != '0000-00-00 00:00:00' &&
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateFirst').val() != 'undefined' ) {
      that.options.endDate.first = $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateFirst' ).val();
      // cogumelo.log('endDateFirst',that.options.endDate.first);
    }
    if( $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateSecond').val() &&
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateSecond').val() != '0000-00-00 00:00:00' &&
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateSecond').val() != 'undefined' ) {
      that.options.endDate.second = $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateSecond' ).val();
      // cogumelo.log('endDateSecond',that.options.endDate.second);
    }
  },

  setFunctionsDates: function() {
    // cogumelo.log('function: setFunctionsDates');
    var that = this;

    that.setInitDate();
    that.setEndDate();
  },
  // Set date INIT
  setInitDate: function() {
    // cogumelo.log('function: setInitDate');
    var that =  this;

    $( that.options.modal + '.initDateFirst' ).on( 'change.datetimepicker', function(e) {
      if( e.date ) {
        e.date.tz(cogumelo.publicConf.date_timezone.project);
        that.options.initDate.first = e.date.format( 'YYYY-MM-DD' );
        // console.log(e.date.format('YYYY-MM-DDTHH:mm:ssZ'));  //  2019-01-07T00:00:00+01:00
      }
      else{
        that.options.initDate.first = '';
      }


      if( that.options.initDate.first !== false || that.options.initDate.first !== '') {
        var finalInitDateFirst = that.options.initDate.first;

        var optionSelected = $( 'select.cgmMForm-field-rextEvent_selectInitTime option:selected' ).val();
        if( optionSelected === 'notTime' && that.options.initDate.first !== ''  ) {
          finalInitDateFirst += 'T00:00:00Z';
        }
        else{
          if( optionSelected === 'rangeTime' ) {
            var inputInitTimeSecond = $( '.initTimeSecond input.datetimepicker-input' ).val();
            if( inputInitTimeSecond !== '' ) {
              $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateSecond' ).val( finalInitDateFirst +'T'+ inputInitTimeSecond +':00Z' );
            }
          }
          var inputInitTimeFirst = $( '.initTimeFirst input.datetimepicker-input' ).val();
          if( inputInitTimeFirst !== '' ) {
            finalInitDateFirst += 'T'+ inputInitTimeFirst +':00Z';
          }
        }
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateFirst' ).val( finalInitDateFirst );
      }
    } );

    that.setInitTimeFirst();
    that.setInitTimeSecond();
  },
  setInitTimeFirst: function() {
    // cogumelo.log('function: setInitTimeFirst');
    var that =  this;

    $( that.options.modal + '.initTimeFirst' ).on( 'change.datetimepicker', function(e) {
      if( e.date ) {
        e.date.tz(cogumelo.publicConf.date_timezone.project);
        that.options.initDate.timeFirst = e.date.format( 'THH:mm:ssZ' );
      }

      if( that.options.initDate.first !== false ) {
        var finalInitDateFirst = moment( that.options.initDate.first ).format( 'YYYY-MM-DD' );

        var optionSelected = $( 'select.cgmMForm-field-rextEvent_selectInitTime option:selected' ).val();
        if( optionSelected === 'notTime' ) {
          finalInitDateFirst += 'T00:00:00Z';
        }
        else if( that.options.initDate.timeFirst !== false ) {
          finalInitDateFirst += that.options.initDate.timeFirst;
        }
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateFirst' ).val( finalInitDateFirst );
      }
    } );
  },
  setInitTimeSecond: function() {
    // cogumelo.log('function: setInitTimeSecond');
    var that =  this;

    $( that.options.modal + '.initTimeSecond' ).on( 'change.datetimepicker', function(e) {
      if( e.date ) {
        e.date.tz(cogumelo.publicConf.date_timezone.project);
        that.options.initDate.timeSecond = e.date.format( 'THH:mm:ssZ' );
      }

      that.options.initDate.second = that.options.initDate.first;
      if( that.options.initDate.second !== false ) {
        var finalInitDateSecond = moment( that.options.initDate.second ).format( 'YYYY-MM-DD' );
        if( that.options.initDate.timeSecond !== false ) {
          finalInitDateSecond += that.options.initDate.timeSecond;
        }
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateSecond' ).val( finalInitDateSecond );
      }
    } );
  },

  // Set date END
  setEndDate: function() {
    // cogumelo.log('function: setEndDate');
    var that =  this;

    $( that.options.modal + '.endDateFirst' ).on( 'change.datetimepicker', function(e) {
      if( e.date ) {
        e.date.tz(cogumelo.publicConf.date_timezone.project);
        that.options.endDate.first = e.date.format( 'YYYY-MM-DD' );
        // console.log(e.date.format('YYYY-MM-DDTHH:mm:ssZ'));  //  2019-01-07T00:00:00+01:00
      }
      else{
        that.options.endDate.first = '';
      }

      if( that.options.endDate.first !== false || that.options.endDate.first !== '' ) {
        var finalEndDateFirst = that.options.endDate.first;

        var optionSelected = $( 'select.cgmMForm-field-rextEvent_selectEndTime option:selected' ).val();
        if( optionSelected === 'notTime' && that.options.endDate.first !== '' ) {
          finalEndDateFirst += 'T00:00:00Z';
        }
        else{
          if( optionSelected === 'rangeTime' ) {
            var inputEndTimeSecond = $( '.endTimeSecond input.datetimepicker-input' ).val();
            if( inputEndTimeSecond !== '' ) {
              $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateSecond' ).val( finalEndDateFirst +'T'+ inputEndTimeSecond +':00Z' );
            }
          }
          var inputInitTimeFirst = $( '.endTimeFirst input.datetimepicker-input' ).val();
          if( inputInitTimeFirst !== '' ) {
            finalEndDateFirst += 'T'+ inputInitTimeFirst +':00Z';
          }
        }
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateFirst' ).val( finalEndDateFirst );
      }
    } );

    that.setEndTimeFirst();
    that.setEndTimeSecond();
  },
  setEndTimeFirst: function() {
    // cogumelo.log('function: setEndTimeFirst');
    var that =  this;

    $( that.options.modal + '.endTimeFirst' ).on( 'change.datetimepicker', function(e) {
      if( e.date ) {
        e.date.tz(cogumelo.publicConf.date_timezone.project);
        that.options.endDate.timeFirst = e.date.format( 'THH:mm:ssZ' );
      }

      if( that.options.endDate.first !== false ) {
        var finalEndDateFirst = moment( that.options.endDate.first ).format( 'YYYY-MM-DD' );

        var optionSelected = $( 'select.cgmMForm-field-rextEvent_selectEndTime option:selected' ).val();
        if( optionSelected === 'notTime' ) {
          finalEndDateFirst += 'T00:00:00Z';
        }
        else if( that.options.endDate.timeFirst !== false ) {
          finalEndDateFirst += that.options.endDate.timeFirst;
        }
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateFirst' ).val( finalEndDateFirst );
      }
    } );
  },
  setEndTimeSecond: function() {
    // cogumelo.log('function: setEndTimeSecond');
    var that =  this;

    $( that.options.modal + '.endTimeSecond' ).on( 'change.datetimepicker', function(e) {
      if( e.date ) {
        e.date.tz(cogumelo.publicConf.date_timezone.project);
        that.options.endDate.timeSecond = e.date.format( 'THH:mm:ssZ' );
      }

      that.options.endDate.second = that.options.endDate.first;
      if( that.options.endDate.second !== false ) {
        var finalEndDateSecond = moment( that.options.endDate.second ).format( 'YYYY-MM-DD' );
        if( that.options.endDate.timeSecond !== false ) {
          finalEndDateSecond += that.options.endDate.timeSecond;
        }
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateSecond' ).val( finalEndDateSecond );
      }
    } );
  },

  checkboxDateRange_onchange: function(){
    // cogumelo.log('function: checkboxDateRange_onchange');
    var that = this;

    var checkValue = $( 'input.cgmMForm-field-rextEvent_dateRange' ).is( ':checked' );
    if( checkValue ) {
      $( '.js-hidden-boxEndDate' ).show();
    }
    else{
      $( '.js-hidden-boxEndDate' ).hide();
    }

    $( 'input.cgmMForm-field-rextEvent_dateRange' ).on( 'click', function() {
      var checked = $( this ).is( ':checked' );
      if( checked ) {
        $( '.js-hidden-boxEndDate' ).show();
      }
      else{
        $( '.js-hidden-boxEndDate' ).hide();
      }
    } );
  },
  selectTime_onchange: function() {
    // cogumelo.log('function: selectTime_onchange');
    var that = this;

    var optionSelectedInitTime = $( 'select.cgmMForm-field-rextEvent_selectInitTime option:selected' ).val();
    that.optionSelectChanged( optionSelectedInitTime, 'initTime' );

    $( 'select.cgmMForm-field-rextEvent_selectInitTime' ).on( 'change', function() {
      var selected = $( this ).find( ' option:selected' ).val();
      that.optionSelectChanged( selected, 'initTime' );
    } );


    var optionSelectedEndTime = $( 'select.cgmMForm-field-rextEvent_selectEndTime option:selected' ).val();
    that.optionSelectChanged( optionSelectedEndTime, 'endTime' );

    $( 'select.cgmMForm-field-rextEvent_selectEndTime' ).on( 'change', function() {
      var selected = $( this ).find( ' option:selected' ).val();
      that.optionSelectChanged( selected, 'endTime' );
    } );
  },
  optionSelectChanged: function( valueOption, typeDate ) {
    // cogumelo.log('function: optionSelectChanged-'+typeDate);
    var that = this;

    var finalDate = '';
    if( typeDate === 'initTime' && that.options.initDate.first !== false ) {
      finalDate = moment( that.options.initDate.first ).format( 'YYYY-MM-DD' );
    }
    else if( typeDate === 'endTime' && that.options.endDate.first !== false ) {
      finalDate = moment( that.options.endDate.first ).format( 'YYYY-MM-DD' );
    }

    if( valueOption === 'notTime' ) {
      if( typeDate === 'initTime' ) {
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateFirst' ).val( finalDate );
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateSecond' ).val('');
      }
      else if( typeDate === 'endTime' ) {
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateFirst' ).val( finalDate );
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateSecond' ).val('');
      }

      $( '.date.'+ typeDate +'First input.datetimepicker-input' ).val('');
      $( '.date.'+ typeDate +'Second input.datetimepicker-input' ).val('');
      $( '.js-hidden-'+ typeDate +'First' ).hide();
      $( '.js-hidden-'+ typeDate +'Second' ).hide();
    }
    else if( valueOption === 'notRangeTime' ) {
      if( typeDate === 'initTime' ) {
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_initDateSecond' ).val('');
      }
      else if( typeDate === 'endTime' ) {
        $( that.options.modal + 'input.cgmMForm-field-rextEvent_endDateSecond' ).val('');
      }

      $( '.js-hidden-'+ typeDate +'First' ).show();
      $( '.date.'+ typeDate +'Second input.datetimepicker-input' ).val('');
      $( '.js-hidden-'+ typeDate +'Second' ).hide();
    }
    else if( valueOption === 'rangeTime' ) {
      $( '.js-hidden-'+ typeDate +'First' ).show();
      $( '.js-hidden-'+ typeDate +'Second' ).show();
    }
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
