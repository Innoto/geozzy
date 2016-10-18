{block name="headCssIncludes" append}
<style type="text/css">
  .communityElement .commImage { position: relative; }
  .communityElement .commImage img { width: 100%; }
  .communityElement .commImage .commDelete {
    position: absolute;
    right: 10px;
    bottom: 10px;
    display: block;
    padding: 2px 4px;
    background-color: white;
    color: #FF2222;
    font-size: 20px;
    cursor: pointer;
  }
</style>
{/block}



<!-- rTypeViewBlock.tpl en rTypeCommunity module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-responsive" alt="Páxina xeral" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png"></img>
          <h1>Tu Comunidad</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="contentSec container gzSection" data-id="{$myInfo.id}">
    <div class="content">
      <p>Mi perfil</p>
      <div class="row">
        <div class="col-sm-4 col-md-2 myInfo">
          <div class="commImage">
            <img src="/cgmlImg/{$myInfo.avatarFileId}/fast_cut/{$myInfo.avatarFileId}.jpg">
          </div>
          <div class="login">{$myInfo.login}</div>
          <a href="/userprofile#user/profile">Editar perfil</a>
        </div>
        <div class="col-sm-8 col-md-10 myInfo" data-id="{$myInfo.id}">
          <div class="commText">
            <div class="name">{$myInfo.name} {$myInfo.surname}</div>
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="myShare">Compartir mis favoritos y redes sociales</div>
              </div>
              <div class="col-sm-12 col-md-6">
                <div class="myFacebook">Bloque Facebook</div>
              </div>
              <div class="col-sm-12 col-md-6">
                <div class="myTwitter">Bloque Twitter</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      {$res.data.content}
    </div>
  </section>

  <script type="text/javascript">
    var geozzy = geozzy || {};
    geozzy.commFollowsInfo = {$commFollowsInfo|@json_encode}
  </script>
  <section class="communitySec gzSection">
    <div class="container commHeader">
    </div>

<hr>

    <div class="container commList">
      <div class="communityList">
        {if $commFollowsInfo|@is_array && $commFollowsInfo|@count gt 0}
        <p>Estás siguiendo...</p>
        <div class="row">
          {foreach $commFollowsInfo as $userInfo}
          {* id,login,name,surname,email,description,avatarFileId *}
          <div class="col-sm-12 col-md-6 communityElement" data-id="{$userInfo.id}">
            <div class="row">
              <div class="col-sm-3">
                <div class="commImage">
                  <img src="/cgmlImg/{$userInfo.avatarFileId}/fast_cut/{$userInfo.avatarFileId}.jpg">
                </div>
              </div>
              <div class="col-sm-9">
                <div class="commText">
                  <div class="name">{$userInfo.name} {$userInfo.surname}<span class="login"> ( {$userInfo.login} )</span></div>
                </div>
                {if $userInfo.favs|@is_array && $userInfo.favs|@count gt 0}
                <div class="favs">
                  <p>Favoritos:</p>
                  <div class="row">
                  {foreach $userInfo.favs as $fav}
                    {if $fav@iteration > 6}
                      {break}{* abort iterating the array *}
                    {/if}
                    <div class="col-sm-2">
                      <div class="commImage">
                        <img src="/cgmlImg/{$fav.image}/fast_cut/{$fav.image}.jpg">
                      </div>
                    </div>
                  {/foreach}
                  </div>
                </div><!-- /favs -->
                {/if}
              </div>
            </div>
          </div>
          {/foreach}
        </div>
        {else}
        <div class="row">
          <div class="communityEmpty">
            <p>Parece que todavía no has añadido a nadie a tu Comunidad...</p>
          </div>
        </div>
        {/if}
      </div>
    </div>

<hr>

    {if $commProposeInfo|@is_array && $commProposeInfo|@count gt 0}
    <div class="container commPropose">
      <div class="communityList">
        <p>Propuestas</p>
        <div class="row">
          {foreach $commProposeInfo as $userInfo}
          {* id,login,name,surname,email,description,avatarFileId *}
          <div class="col-sm-6 col-md-3 communityElement" data-id="{$userInfo.id}">
            <div class="commImage">
              <img src="/cgmlImg/{$userInfo.avatarFileId}/fast_cut/{$userInfo.avatarFileId}.jpg">
            </div>
            <div class="commText">
              <div class="login">{$userInfo.login}</div>
              <div class="name">{$userInfo.name} {$userInfo.surname}</div>
            </div>
          </div>
          {/foreach}
        </div>
      </div>
    </div>
    {/if}

    <div class="container commFooter">
    </div>
  </section>


</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypeCommunity module -->
