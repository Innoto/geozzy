<!-- collectionFormBlockBase.tpl en admin module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
  .cgmMForm-wrap { margin-bottom: 10px; }
</style>

{$formOpen}

{foreach $formFieldsArray as $formField}
  {$formField}
{/foreach}

{$formClose}

{$formValidations}


<!-- /collectionFormBlockBase.tpl en admin module -->
