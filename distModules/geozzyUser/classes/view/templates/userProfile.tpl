{$client_includes}
<!--<script rel="false" type="text/javascript" src="{$cogumelo.publicConf.media}/js/resource.js"></script>-->
<div class="container">
  <h1>{t}Perfil de Usuario{/t}</h1>
  {$userBaseFormOpen}
    <div class="userFormBase">
      <div class="row">
        {$userBaseFormFields.cgIntFrmId}
        <div class="col-md-12">
          <div class="row row-centered">
            <div class="col-md-4 col-centered">
              {$userBaseFormFields.avatar}
            </div>
          </div>
        </div>
        <div class="col-md-6">
          {if $nickname}
            {$userBaseFormFields.login}
          {/if}
          {$userBaseFormFields.email}
          {$userBaseFormFields.repeatEmail}
          {$userBaseFormFields.name}
          {$userBaseFormFields.surname}

        </div>
        <div class="col-md-6">
          <div class="changePasswordProfileForm">
            <p>{t}Write only if you want to change the password{/t}</p>
            {$userBaseFormFields.password}
            {$userBaseFormFields.password2}
          </div>
        </div>
        <div class="col-md-12">
          {foreach $cogumelo.publicConf.langAvailableIds as $lang}
            {$userBaseFormFields["description_$lang"]}
          {/foreach}
          {$userBaseFormFields.submit}
        </div>
      </div>
    </div>
  {$userBaseFormClose}
  {$userBaseFormValidations}

  {if isset($profileBlock)}
  <hr />
  {$profileBlock}
  <hr />
  {/if}
</div>
