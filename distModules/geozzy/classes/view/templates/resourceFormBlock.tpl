<!-- newRecursoFormBlock.tpl en geozzy module -->

<style>
/*
  body { font-size: 12px; }
  .cgmMForm-wrap { border:1px dashed violet; margin:1px; padding:1px 5px; }
  .cgmMForm-group-wrap { border:2px dotted blue; }

  input[type="text"], textarea { width: 200px; }
  input[type="text"] { margin: 2px; padding: 2px; font-size: 12px; }
  .error, .formError { color:red; border:2px solid red; }
  .cgmMForm-inputFicheiro { background-color:#FFD; }
  textarea { height:155px; width: 90% }

  .cgmMForm .cke_editable_inline { border:2px dotted green; background-color: white; cursor: pointer; }
*/

  label { display: block; }
  .cgmMForm-field { max-width: none !important; }

  .cgmMForm-wrap { margin-bottom: 10px; }

  .cgmMForm-group-wrap > label { display: none; }
  .cgmMForm-group-wrap .langSwitch { float: right; margin:0; }
  .cgmMForm-group-wrap .langSwitch li {
    display: none; padding: 0 0.5em; cursor: pointer; font-weight: bold;
    border:1px dotted #1E90FF;
    background-color: #F5F5F5;
  }
  .cgmMForm-group-wrap .langSwitch:hover li { display: inline; }
  .cgmMForm-group-wrap .langSwitch .langActive {
    display: inline; cursor: no-drop;
    background-color: inherit;
    color: #1E90FF;
  }
  .cgmMForm-group-wrap .langSwitchIcon { float:right; color:#1E90FF }
</style>



{$formOpen}

  {$formFields}

{$formClose}

{$formValidations}



<!-- script type="text/javascript" src="/ckeditor/ckeditor.js"></script -->

<script>
$( document ).ready( function() {
  var langAvailable = {$JsLangAvailable};
  var langDefault = {$JsLangDefault};
  var langForm = false;

  function switchFormLang( lang ) {
    console.log( 'switchFormLang: '+lang );
    langForm = lang;
    $( '.cgmMForm-groupElem > div' ).hide();
    $( '.cgmMForm-groupElem > div[class$="_'+lang+'"]' ).show();
    $( '.cgmMForm-group-wrap ul.langSwitch li' ).removeClass( 'langActive' );
    $( '.cgmMForm-group-wrap ul.langSwitch li.langSwitch-'+lang ).addClass( 'langActive' );
  }

  if( langAvailable ) {
    var htmlLangSwitch = '';
    htmlLangSwitch += '<div class="langSwitch-wrap">';
    htmlLangSwitch += '<ul class="langSwitch">';
    $.each( langAvailable, function( index, lang ) {
      htmlLangSwitch += '<li class="langSwitch-'+lang+'" data-lang-value="'+lang+'">'+lang;
    });
    htmlLangSwitch += '</ul>';
    htmlLangSwitch += '<span class="langSwitchIcon"><i class="fa fa-flag fa-fw"></i></span>';
    htmlLangSwitch += '</div>';
    $( '.cgmMForm-group-wrap' ).prepend( htmlLangSwitch );

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


<!-- /newRecursoFormBlock.tpl en geozzy module -->
