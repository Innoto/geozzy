{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-user'}{/if}
  {if !isset($title)}{assign var='title' value='Data User'}{/if}
{/block}

{block name="content"}
  {$editUserHtml}
{/block}
