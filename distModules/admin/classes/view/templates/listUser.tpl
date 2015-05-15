{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-user'}{/if}
  {if !isset($title)}{assign var='title' value='Users'}{/if}
{/block}

{block name="content"}
<div class="usersTable">
  {$userTable}
</div>
{/block}
