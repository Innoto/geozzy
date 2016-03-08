{extends file="adminPanel.tpl"}

{block "header" append}
  <script type="text/javascript" src="{$mediaJs}/module/admin/js/adminUser.js"></script>
{/block}

{block name="content"}

{$userFormOpen}
{$userFormFields.cgIntFrmId}
<div class="row">
  <div class="col-md-6">

    {$userFormFields.login}
    {$userFormFields.email}
    {$userFormFields.password}
    {$userFormFields.password2}
    {$userFormFields.name}
    {$userFormFields.surname}


    {$userFormFields.active}

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
