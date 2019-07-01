{extends file="admin///adminPanel.tpl"}


{block name="content"}
<!-- rExtFormBase.tpl en geozzt module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
</style>
<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/admin/js/adminResource.js"></script>
<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/rextMap/js/rExtMapWidgetForm.js"></script>
<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/rextMap/js/rExtMapWidgetFormPositionView.js"></script>


{$res.dataForm.formOpen}
{* hidden fields required *}
{$res.dataForm.formFieldsArray.cgIntFrmId}
{if isset($res.dataForm.formFieldsArray.id)}{$res.dataForm.formFieldsArray.id}{/if}
{if isset($res.dataForm.formFieldsArray.rTypeIdName)}{$res.dataForm.formFieldsArray.rTypeIdName}{/if}
{* /hidden fields required *}

{if isset($formFieldsNames)}
{foreach $formFieldsNames as $name}
  {$res.dataForm.formFieldsArray[$name]}
{/foreach}
{/if}

{if !empty($res.dataForm.formFieldsArray.urlRes)}
  {$res.dataForm.formFieldsArray.urlRes}
{/if}
{$res.dataForm.formFieldsArray.cancel}
{$res.dataForm.formFieldsArray.submit}


{$res.dataForm.formClose}

{$res.dataForm.formValidations}


<!-- rExtFormBase.tpl en geozzt module -->
{/block}{*/content*}
