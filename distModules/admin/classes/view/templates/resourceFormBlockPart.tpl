
{foreach $formFieldsArray as $key=>$formField}
  {if !in_array($key,$formFieldsHiddenArray)}
    {$formField}
  {/if}  
{/foreach}
