{extends file="adminPanel.tpl"}

{block "header" append}
  <script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/admin/js/adminUser.js"></script>
{/block}

{block name="content"}

{$userFormOpen}
{$userFormFields.cgIntFrmId}
<div class="row">
  <div class="col-md-6">

    {$userFormFields.email}
    {$userFormFields.repeatEmail}
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


{if $cogumelo.publicConf.langAvailableIds === false || $cogumelo.publicConf.langAvailableIds|@count gt 0}
  {foreach $cogumelo.publicConf.langAvailableIds as $lang}
    {$userFormFields['description_'|cat:$lang]}
  {/foreach}
{/if}

{$userFormFields.submit}

{$userFormClose}
{$userFormValidations}

{/block}
