{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($title)}{assign var='title' value={$user['data']['login']}}{/if}

{/block}

{block name="content"}
<div class="row">
  <div class="col-lg-4">
    {if array_key_exists('avatar', $user['data'])}
      <img class="userAvatar img-fluid rounded" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$user['data']['avatar']}/fast_cut/{$user['data']['avatar']}">
    {/if}
  </div>
  <div class="col-lg-8">
    <form class="form-horizontal" role="form">
      <div class="form-group">
        <label class="col-md-4 col-form-label">Name:</label>
        <div class="col-md-8">
          <p class="form-control-plaintext">{$user['data']['name']} {$user['data']['surname']}</p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-4 col-form-label">Login:</label>
        <div class="col-md-8">
          <p class="form-control-plaintext">{$user['data']['login']}</p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-4 col-form-label">Email:</label>
        <div class="col-md-8">
          <p class="form-control-plaintext">{$user['data']['email']}</p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-4 col-form-label">Description:</label>
        <div class="col-md-8">
          <p class="form-control-plaintext">{if !empty($user['data']['description_es'])}{$user['data']['description_es']}{/if}</p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-4 col-form-label">Last login:</label>
        <div class="col-md-8">
          <p class="form-control-plaintext">{$user['data']['timeLastLogin']}</p>
        </div>
      </div>
    </form>

  </div>
</div>


{/block}
