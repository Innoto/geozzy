{extends file="adminPanel.tpl"}

{block name="content"}

{$userFormOpen}
{$userFormFields.cgIntFrmId}
<div class="row">
  <div class="col-md-6">

    {$userFormFields.login}
    {$userFormFields.password}
    {$userFormFields.password2}
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
