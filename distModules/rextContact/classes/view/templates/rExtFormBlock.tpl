<!-- rExtFormBlock.tpl en rExtContact module -->

<div class="rExtContact formBlock">

{foreach $rExt.dataForm.formFieldsArray as $key=>$formField}
  {if !in_array($key,$formFieldsHiddenArray)}
    {$formField}
  {/if}
{/foreach}

</div>

<!-- /rExtFormBlock.tpl en rExtContact module -->
