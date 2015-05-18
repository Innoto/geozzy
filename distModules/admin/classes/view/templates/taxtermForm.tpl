{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-tag'}{/if}
  {if !isset($title)}{assign var='title' value='Category form'}{/if}
{/block}

{block name="content"}
  {$taxtermFormHtml}
{/block}
