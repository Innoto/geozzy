<!-- rExtFormBlock.tpl en RExtAppGeozzySample -->

{$client_includes}
<div class="{$rExtName} formBlock">
OOOOOOOOOOOOOLA
{if isset($prevContent)}
  {$prevContent}
{/if}

{foreach $rExt.dataForm.formFieldsArray as $key=>$formField}
    {if isset($formFieldsHiddenArray) && !in_array($key,$formFieldsHiddenArray) || !isset($formFieldsHiddenArray)}
      {$formField}
    {/if}
{/foreach}
OLA
<input name="submit" value="Send" form="resourceEdit" class="cgmMForm-field cgmMForm-field-submit gzzAdminToMove btn btn-primary" type="submit">

{if isset($postContent)}
  {$postContent}
{/if}

</div>

<!-- /rExtFormBlock.tpl en RExtAppGeozzySample -->
