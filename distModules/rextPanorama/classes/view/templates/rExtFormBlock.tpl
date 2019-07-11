<!-- rExtFormBlock.tpl en RExtPanorama -->

{$client_includes}
<div class="{$rExtName} formBlock">
{if isset($prevContent)}
  {$prevContent}
{/if}

{foreach $rExt.dataForm.formFieldsArray as $key=>$formField}
    {if isset($formFieldsHiddenArray) && !in_array($key,$formFieldsHiddenArray) || !isset($formFieldsHiddenArray)}
      {$formField}
    {/if}
{/foreach}

{if isset($postContent)}
  {$postContent}
{/if}

</div>

<!-- /rExtFormBlock.tpl en RExtPanorama -->
