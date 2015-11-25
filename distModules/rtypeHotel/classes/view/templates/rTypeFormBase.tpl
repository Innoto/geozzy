{extends file="admin///adminPanel.tpl"}


{block name="content"}
<!-- rExtFormBlock.tpl en admin module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
</style>
<script type="text/javascript" src="{$mediaJs}/module/admin/js/adminResource.js"></script>


{$res.dataForm.formOpen}
{if isset($res.dataForm.formFieldsArray.id)}{$res.dataForm.formFieldsArray.id}{/if}

{if isset($formFieldsNames)}
{foreach $formFieldsNames as $name}
  {$res.dataForm.formFieldsArray[$name]}
{/foreach}
{/if}

{$res.dataForm.formFieldsArray.submit}

{$res.dataForm.formClose}

{$res.dataForm.formValidations}


<!-- /rExtFormBlock.tpl en admin module -->
{/block}{*/content*}
