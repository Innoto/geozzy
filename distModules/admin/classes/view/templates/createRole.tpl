{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-unlock-alt'}{/if}
  {if !isset($title)}{assign var='title' value='Create Role'}{/if}
{/block}


{block name="content"}
  {$createRoleHtml}
{/block}
