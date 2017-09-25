<!-- newRecursoFormBlock.tpl en geozzy module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
</style>

<script>
/*  var langAvailable = {$JsLangAvailable};
  var langDefault = {$JsLangDefault};*/
  $('select.gzzSelect2').select2();
</script>

{$taxtermFormOpen}
  {foreach from=$taxtermFormFieldsArray item=field key=idField}
    {if $idField !== 'idName'}
      {$field}
    {/if}
  {/foreach}
  {if isset($taxtermFormFieldsArray.idName)}{$taxtermFormFieldsArray.idName}{/if}
{$taxtermFormClose}
{$taxtermFormValidations}
