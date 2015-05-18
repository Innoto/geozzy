
{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-unlock-alt'}{/if}
  {if !isset($title)}{assign var='title' value='Assign Roles'}{/if}
{/block}


{block name="content"}
  {$userRolesFormHtml}
{/block}
