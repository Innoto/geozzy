var geozzy = geozzy || {};
var level = '';
var myval= '';
$(document).ready(function(){

  $( '.cgmMForm-field' ).on( 'change', function() {
    $( '.formProfesorInscripcion .submitForm input.cgmMForm-field-submit' ).attr( 'disabled', 'disabled' ).addClass('sendDisabled');
  } );

  // 3.universidad del máster y especialidad
  if($('input.cgmMForm-field-rExtAppProfesor_otraUniversidad').val()=='0'){
    $('input.cgmMForm-field-rExtAppProfesor_otraUniversidad').val('');
  }
  if($('input.cgmMForm-field-rExtAppProfesor_otraEspecialidad').val()=='0'){
    $('input.cgmMForm-field-rExtAppProfesor_otraEspecialidad').val('');
  }
  if($('select.cgmMForm-field-rExtAppProfesor_appProfesorUniversidad option:selected').attr('data-term-idname')=='otra'){
    $('input.cgmMForm-field-rExtAppProfesor_otraUniversidad').removeAttr("disabled");
  }
  else{
    $('input.cgmMForm-field-rExtAppProfesor_otraUniversidad').attr('disabled','disabled');
  }

  $('input.cgmMForm-field-rExtAppProfesor_otraUniversidad').attr('disabled','disabled');

  $('select.cgmMForm-field-rExtAppProfesor_appProfesorUniversidad').change(function(){
    if($('select.cgmMForm-field-rExtAppProfesor_appProfesorUniversidad option:selected').attr('data-term-idname')=='otra'){
      $('input.cgmMForm-field-rExtAppProfesor_otraUniversidad').removeAttr("disabled");
    }
    else{
      $('input.cgmMForm-field-rExtAppProfesor_otraUniversidad').val('');
      $('input.cgmMForm-field-rExtAppProfesor_otraUniversidad').attr('disabled','disabled');
    }
  });
  // especialidad
  if($('select.cgmMForm-field-rExtAppProfesor_appProfesorEspecialidad option:selected').attr('data-term-idname')=='otra'){
    $('.cgmMForm-field-rExtAppProfesor_otraEspecialidad').removeAttr("disabled");
  }
  else{
    $('.cgmMForm-field-rExtAppProfesor_otraEspecialidad').attr('disabled','disabled');
  }

  $('select.cgmMForm-field-rExtAppProfesor_appProfesorEspecialidad').change(function(){
    if($('select.cgmMForm-field-rExtAppProfesor_appProfesorEspecialidad option:selected').attr('data-term-idname')=='otra'){
      $('.cgmMForm-field-rExtAppProfesor_otraEspecialidad').removeAttr("disabled");
    }
    else{
      $('.cgmMForm-field-rExtAppProfesor_otraEspecialidad').val('');
      $('.cgmMForm-field-rExtAppProfesor_otraEspecialidad').attr('disabled','disabled');
    }
  });


  // 5.Nivel de inglés
  selectedLevel = $("input[name=rExtAppProfesor_nivelIngles]:checked").val();
  if(selectedLevel && selectedLevel!=''){
    setEnglishTestSelector(selectedLevel);
  }
  else{
    $('.cgmMForm-field-rExtAppProfesor_tituloIngles').hide();
  }

  $("input[name=rExtAppProfesor_nivelIngles]").on('change',function(i,e){
    $(".cgmMForm-field-rExtAppProfesor_tituloIngles option:selected").removeAttr('selected');
    $($(".cgmMForm-field-rExtAppProfesor_tituloIngles option")[0]).attr('selected', true);
    $('.cgmMForm-field-rExtAppProfesor_tituloIngles').show();
    level = $(this).val();
    setEnglishTestSelector(level);
  });
});

function setEnglishTestSelector(level){
  $(".cgmMForm-field-rExtAppProfesor_tituloIngles option").each(function(i, elem){
    if($(elem).attr('value') && $(elem).attr('value')!=''){
      myval = $(elem).attr('value');
    }
    if(myval == 'pet' || myval == 'fce' || myval == 'uni' || myval == 'eoi'){
      if(level=='b1'||level=='b2'){
        $(elem).removeAttr("disabled");
        $(elem).css('color', '#4E4E4E');
      }
      else{
        if($(elem).val()!=''){
          $(elem).attr('disabled','disabled');
          $(elem).css('color','#e0e0e0');
        }
      }
    }
    if(myval == 'cae' || myval == 'cpe'){
      if(level=='b1'||level=='b2'){
        if($(elem).val()!=''){
          $(elem).attr('disabled','disabled');
          $(elem).css('color','#e0e0e0');
        }
      }
      else{
        $(elem).removeAttr("disabled");
        $(elem).css('color', '#4E4E4E');

      }

    }
  });
}


function envioValido( idForm ) {
  // Quitamos el atributo disabled y la clase que le da apariencia deshabilitado
  $( '.formProfesorInscripcion .submitForm input.cgmMForm-field-submit' ).removeAttr('disabled').removeClass('sendDisabled');

  new geozzy.generateModal( {
    classCss: "inscripcionOK",
    htmlBody: __( "Tu formulario de participación ha sido guardado."),
    successCallback: function(){ return false; }
  } );
}
