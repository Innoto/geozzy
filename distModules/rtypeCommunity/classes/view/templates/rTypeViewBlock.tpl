<!-- rTypeViewBlock.tpl en rTypeCommunity module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-responsive" alt="Tu Comunidad" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png"></img>
          <h1>Tu Comunidad</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="communitySec gzSection">
    <div class="container commHeader">
      <p>Mi perfil</p>
      <div class="row">
        <div class="myInfo" data-id="{$myInfo.id}">
          <div class="col-sm-5 col-md-4">
            <div class="infoPersonal">
              <div class="commImage">
                <img src="/cgmlImg/{$myInfo.avatarFileId}/fast_cut/{$myInfo.avatarFileId}.jpg">
              </div>
              <div class="login">{$myInfo.login}</div>
              <a href="/userprofile#user/profile">Editar perfil</a>
            </div>
          </div>
          <div class="col-sm-7 col-md-8">
            <div class="commText">
              <div class="name">{$myInfo.name} {$myInfo.surname}</div>
              <div class="row">
                <div class="commRS">
                  <div class="col-sm-9 col-md-9">
                    <div class="myShare">Compartir mis favoritos y redes sociales:
                      <span class="view">
                        <span class="shareOn">Si</span>
                        <span class="shareOff">No</span>
                        <style type="text/css">
                          {if $myInfo.comm.share && $myInfo.comm.share == 1}
                          .myShare .shareOff { display: none; }
                          {else}
                          .myShare .shareOn { display: none; }
                          {/if}
                        </style>
                      </span>
                      <span class="edit">
                        <input type="radio" name="shareStatus" class="shareStatusOn"
                          value="1"{if $myInfo.comm.share && $myInfo.comm.share == 1} checked{/if}>
                          Si
                        <input type="radio" name="shareStatus" class="shareStatusOff"
                          value="0"{if !$myInfo.comm.share || $myInfo.comm.share != 1} checked{/if}>
                          No
                      </span>
                    </div>
                  </div>
                  <div class="col-sm-3 col-md-3">
                    <div class="actions">
                      <span class="view actionEdit">Editar</span>
                      <span class="edit actionSave">Guardar</span>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <div class="myFacebook">
                      Facebook:
                      <span class="view facebookAccount">{$myInfo.comm.facebook|default:'No indicado'}</span>
                      <span class="edit">
                        <input type="text" name="facebookAccount" value="{$myInfo.comm.facebook|default:''}">
                      </span>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <div class="myTwitter">
                      Twitter:
                      <span class="view twitterAccount">{$myInfo.comm.twitter|default:'No indicado'}</span>
                      <span class="edit">
                        <input type="text" name="twitterAccount" value="{$myInfo.comm.twitter|default:''}">
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {if $res.data.content}
    <div class="content">
      {$res.data.content}
    </div>
    {/if}

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
                <div class="actionFollow" data-id="{$userInfo.id}" data-follow="1">
                  <span class="showStatus off" style="display: none;">Seguir</span><span class="showStatus on">Dejar de seguir</span>
                </div>
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
                {if $userInfo.comm}
                <div class="socialLinks">
                  {if $userInfo.comm.facebook}
                    <a href="https://www.facebook.com/{$userInfo.comm.facebook}" target="_blank">Facebook</a>
                  {/if}
                  {if $userInfo.comm.twitter}
                    <a href="https://twitter.com/{$userInfo.comm.twitter}" target="_blank">Twitter</a>
                  {/if}
                </div>
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
            <div class="actionFollow" data-id="{$userInfo.id}" data-follow="0">
              <span class="showStatus off">Seguir</span><span class="showStatus on" style="display: none;">Siguiendo</span>
            </div>
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



<script type="text/javascript">

var geozzy = geozzy || {};
geozzy.rTypeCommunityData = {
  'userSessionId': '{$cogumelo.publicConf.user_session_id|default:''}',
  'myCommunity': {$myInfo.comm|@json_encode},
  'commFollowsInfo': {$commFollowsInfo|@json_encode},
  'commProposeInfo': {$commProposeInfo|@json_encode},
}

$('.commRS .edit').hide();
$('.commRS .view').show();
</script>

<!-- /rTypeViewBlock.tpl en rTypeCommunity module -->
