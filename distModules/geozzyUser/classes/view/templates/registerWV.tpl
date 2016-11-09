
<div class="container">
  {$userFormOpen}
    {foreach from=$userFormFields key=key item=field}
      {$field}
    {/foreach}
  {$userFormClose}
  {$userFormValidations}
</div>  
