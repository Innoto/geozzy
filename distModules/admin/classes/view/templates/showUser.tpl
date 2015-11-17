{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($title)}{assign var='title' value={$user->getter('login')}}{/if}

{/block}

{block name="content"}
<div class="row">
  <div class="col-md-4">
    {if $user->getter('avatar')}
      <img class="userAvatar img-responsive img-rounded" src="/cgmlImg/{$user->getter('avatar')}">
    {/if}
  </div>
  <div class="col-md-8">
    <form class="form-horizontal" role="form">
      <div class="form-group">
        <label class="col-sm-4 control-label">Name:</label>
        <div class="col-sm-8">
          <p class="form-control-static">{$user->getter('name')} {$user->getter('surname')}</p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Login:</label>
        <div class="col-sm-8">
          <p class="form-control-static">{$user->getter('login')} </p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Email:</label>
        <div class="col-sm-8">
          <p class="form-control-static">{$user->getter('email')}</p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Description:</label>
        <div class="col-sm-8">
          <p class="form-control-static">{$user->getter('description')} </p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Last login:</label>
        <div class="col-sm-8">
          <p class="form-control-static">{$user->getter('timeLastLogin')} </p>
        </div>
      </div>
    </form>

  </div>
</div>


{/block}
