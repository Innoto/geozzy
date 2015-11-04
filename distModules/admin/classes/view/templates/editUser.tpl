{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-user'}{/if}
  {if !isset($title)}{assign var='title' value='Data User'}{/if}
{/block}


{block name="content"}
{$userFormOpen}

{$userFormFields.cgIntFrmId}
<div class="row">
  <div class="col-md-6">

    {$userFormFields.login}
    {$userFormFields.name}
    {$userFormFields.surname}
    {$userFormFields.email}


  </div>
  <div class="col-md-6">
    {$userFormFields.avatar}
  </div>
</div>

{if $langAvailableIds === false || $langAvailableIds|@count gt 1}
  {foreach $langAvailableIds as $lang}
    {$userFormFields['description_'|cat:$lang]}
  {/foreach}
{else}
  {$userFormFields.description}
{/if}

{$userFormFields.submit}

{$userFormClose}
{$userFormValidations}


{/block}
