<!-- rExtFormBlock.tpl en  module -->

<div class="{$rExtName} formBlock">

{foreach $rExt.dataForm.formFieldsArray as $key=>$formField}
  {if !in_array($key,$formFieldsHiddenArray)}
    {$formField}
  {/if}
{/foreach}

</div>

<!-- /rExtFormBlock.tpl en  module -->
