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
{$userFormFields.description}
{$userFormFields.submit}

{$userFormClose}
{$userFormValidations}

{/block}
