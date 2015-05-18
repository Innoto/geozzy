{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-key'}{/if}
  {if !isset($title)}{assign var='title' value='Change Password'}{/if}
{/block}


{block name="content"}
  {$changePasswordHtml}
{/block}
