<!-- resourceFormBlockBase.tpl en admin module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
</style>

<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/admin/js/adminResource.js"></script>
<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/rextMap/js/rExtMapWidgetForm.js"></script>
<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/rextMap/js/rExtMapWidgetFormPositionView.js"></script>

{$formOpen}

{foreach $formFieldsArray as $key=>$formField}
  {if !in_array($key,$formFieldsHiddenArray)}
    {$formField}
  {/if}
{/foreach}

{$formClose}

{$formValidations}


<!-- /resourceFormBlockBase.tpl en admin module -->
