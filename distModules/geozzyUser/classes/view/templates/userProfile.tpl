{$client_includes}
<!--<script rel="false" type="text/javascript" src="{$cogumelo.publicConf.media}/js/resource.js"></script>-->
<div class="titleBar">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <img class="iconTitleBar img-responsive" alt="Páxina xeral" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png"></img>
        <h1>{t}Perfil de Usuario{/t}</h1>
      </div>
    </div>
  </div>
</div>

<div class="container">
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

  <hr />
  {$profileBlock}
  <hr />
</div>
