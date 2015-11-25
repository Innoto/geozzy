{extends file="admin///adminPanel.tpl"}


{block name="content"}

{if isset($formFieldsNames)}
{foreach $formFieldsNames as $name}
  {$res.dataForm.formFieldsArray[$name]}
{/foreach}
{/if}

{/block}{*/content*}
