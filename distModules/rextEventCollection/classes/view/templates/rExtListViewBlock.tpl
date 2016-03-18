<div class="rExtEventCollection formBlock">

LISTA

{foreach $rExt.dataForm.formFieldsArray as $key=>$formField}
  {if !in_array($key,$formFieldsHiddenArray)}
    {$formField}
  {/if}
{/foreach}

</div>
