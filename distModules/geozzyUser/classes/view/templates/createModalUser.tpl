{$userFormOpen}
{foreach from=$userFormFields key=key item=field}
  {if $key!='submit'}{$field}{/if}
{/foreach}
{$formCaptcha}
{$userFormFields.submit}
{$userFormClose}
{$userFormValidations}
