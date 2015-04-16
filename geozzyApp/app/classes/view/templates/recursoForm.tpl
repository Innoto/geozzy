{extends "default.tpl"} 


{block "headTitle" append} - Form de Recurso{/block}


{block "headCssIncludes" append}
  <style>
    body { font-size: 12px; }
    .cgmMForm-wrap { border:1px dashed violet; margin:1px; padding:1px 5px; }
    .cgmMForm-group-wrap { border:2px dotted blue; }
    label { display: inline; margin: 0;}
    input[type="text"], textarea { width: 200px; }
    input[type="text"] { margin: 2px; padding: 2px; font-size: 12px; }
    .error, .formError { color:red; border:2px solid red; }
    .cgmMForm-inputFicheiro { background-color:#FFD; }
    textarea { height:55px; }
    /*
    .cgmMForm-groupElem { position: relative; margin-top: 20px; height: 70px; }
    .cgmMForm-groupElem .cgmMForm-wrap { position: absolute; top: 0; left: 0; }
    */
    .cgmMForm-group-wrap > label { display: none; }
    .cgmMForm-group-wrap .langSwitch { margin:0; }
    .cgmMForm-group-wrap .langSwitch li { display: inline; padding: 0 0.5em; border:1px dotted pink; }
  </style>
{/block}


{block "bodyContent"}

  {$formOpen}

    {$formFields}

  {$formClose}

  {$formValidations}

<script>
$( document ).ready( function() {
  var langAvailable = {$JsLangAvailable};
  var langDefault = {$JsLangDefault};
  var langForm = false;

  if( langAvailable ) {
    var htmlLangSwitch = '<ul class="langSwitch">';
    $.each( langAvailable, function( index, value ) {
      htmlLangSwitch += '<li class="langSwitch-'+value+'" data-lang-value="'+value+'">'+value;
    });
    $( '.cgmMForm-group-wrap' ).prepend( htmlLangSwitch );

    function switchFormLang( lang ) {
      console.log( 'switchFormLang: '+lang );
      langForm = lang;
      $( '.cgmMForm-groupElem > div' ).hide();
      $( '.cgmMForm-groupElem > div[class$="_'+lang+'"]' ).show();
    }

    switchFormLang( langDefault );

    $( '.cgmMForm-group-wrap ul.langSwitch li' ).on( "click", function() {
      newLang = $( this ).data( 'lang-value' );
      if( newLang != langForm ) {
        switchFormLang( newLang );
      }
    });
  }
});
</script>

{/block}

