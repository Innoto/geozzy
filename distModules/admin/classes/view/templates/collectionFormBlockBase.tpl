<!-- collectionFormBlockBase.tpl en admin module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
</style>
<script type="text/javascript" src="{$mediaJs}/module/admin/js/adminCollection.js"></script>


{$formOpen}

{foreach $formFieldsArray as $formField}
  {$formField}
{/foreach}

{$formClose}

{$formValidations}


<!-- /collectionFormBlockBase.tpl en admin module -->
