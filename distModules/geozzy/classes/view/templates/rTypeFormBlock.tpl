<!-- rExtFormBlock.tpl en admin module -->

<style>
  label { display: block; }
  .cgmMForm-field { max-width: none !important; }
</style>
<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/admin/js/adminResource.js"></script>
<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/rextMap/js/rExtMapWidgetForm.js"></script>
<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/rextMap/js/rExtMapWidgetFormPositionView.js"></script>


{$res.dataForm.formOpen}

{foreach $res.dataForm.formFieldsArray as $key=>$formField}
  {$formField}
{/foreach}

{$res.dataForm.formClose}

{$res.dataForm.formValidations}


<!-- /rExtFormBlock.tpl en admin module -->
