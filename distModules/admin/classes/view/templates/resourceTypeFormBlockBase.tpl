<!-- resourceFormBlockBase.tpl en admin module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
</style>
<script type="text/javascript" src="/media/module/admin/js/adminResourceType.js"></script>


{$formOpen}

{foreach $formFieldsArray as $formField}
  {$formField}
{/foreach}

{$formClose}

{$formValidations}


<!-- /resourceFormBlockBase.tpl en admin module -->