<!-- rTypeViewBlock.tpl en rTypeCommunity module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-fluid" alt="Tu Comunidad" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png">
          <h1>{t}Tu Comunidad{/t}</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="communitySec gzSection">
    <div class="commHeader">
      <div class="container">
        <h3>{t}Mi perfil{/t}</h3>
        <div class="row">
          <div class="myInfo" data-id="{$myInfo.id}">
            <div class="col-sm-5 col-md-4">
              <div class="infoPersonal">
                <div class="commImage">
                  <img class="img-fluid" src="{if $myInfo.avatarFileId}/cgmlImg/{$myInfo.avatarFileId}/userPhotoCommunity/{$myInfo.avatarFileId}.jpg{else}/mediaCache/module/rextCommunity/img/user.png{/if}">
                </div>
              </div>
            </div>
            <div class="col-sm-7 col-md-8">
              <div class="commText">
                <div class="row">
                  <div class="commRS">
                    <div class="col-md-6">
                      <div class="row">
                        <div class="shareComm">
                          <div class="col-xs-12 ">
                            <div class="actions">
                              <span class="view actionEdit"><i class="fas fa-lg fa-pencil-alt" aria-hidden="true"></i> {t}Editar{/t}</span>
                              <span class="edit actionSave"><i class="far fa-lg fa-save" aria-hidden="true"></i> {t}Guardar{/t}</span>
                            </div>
                          </div>
                          <div class="col-xs-12">
                            <div class="myShare">
                              <span class="view">
                                <span class="shareOn"><i class="fas fa-lg fa-check" aria-hidden="true"></i></span>
                                <span class="shareOff"><i class="fas fa-lg fa-times" aria-hidden="true"></i></span>
                              </span>
                              <style type="text/css">
                                {if $myInfo.comm.share && $myInfo.comm.share == 1}
                                  .myShare .shareOff { display: none; }
                                {else}
                                  .myShare .shareOn { display: none; }
                                {/if}
                              </style>
                              <span class="textShare">{t}Compartir mis datos con la comunidad{/t}</span>
                              <span class="edit">
                                <label>
                                  <input type="radio" name="shareStatus" class="shareStatusOn"
                                  value="1"{if $myInfo.comm.share && $myInfo.comm.share == 1} checked{/if}>
                                  <i class="fas fa-lg fa-check" aria-hidden="true"></i>
                                </label>
                                <label>
                                  <input type="radio" name="shareStatus" class="shareStatusOff"
                                  value="0"{if !$myInfo.comm.share || $myInfo.comm.share != 1} checked{/if}>
                                  <i class="fas fa-lg fa-times" aria-hidden="true"></i>
                                </label>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="row">
                        <div class="rs">
                          <div class="col-xs-12">
                            <div class="myFacebook noShare">
                              <span class="iconRs rsFacebook"><i class="fab fa-fw fa-3x fa-facebook-f" aria-hidden="true"></i></span>
                              <div class="view">
                                <div class="shareOn">{t}Perfil enlazado con Facebook{/t}</div>
                                <div class="shareOff">{t}Enlazar mi perfil con Facebook{/t}</div>
                              </div>
                              <span class="edit">
                                <input type="text" name="facebookAccount" value="{$myInfo.comm.facebook|default:''}">
                              </span>
                            </div>
                            <style type="text/css">
                              {if $myInfo.comm.facebook && $myInfo.comm.facebook == 1}
                                .myFacebook .shareOff { display: none; }
                              {else}
                                .myFacebook .shareOn { display: none; }
                              {/if}
                            </style>
                          </div>
                          <div class="col-xs-12">
                            <div class="myTwitter noShare">
                              <span class="iconRs rsTwitter"><i class="fab fa-fw fa-3x fa-twitter" aria-hidden="true"></i></span>
                              <div class="view">
                                <span class="shareOn">{t}Perfil enlazado con Twitter{/t}</span>
                                <span class="shareOff">{t}Enlazar mi perfil con Twitter{/t}</span>
                              </div>
                              <span class="edit">
                                <input type="text" name="twitterAccount" value="{$myInfo.comm.twitter|default:''}">
                              </span>
                            </div>
                            <style type="text/css">
                              {if $myInfo.comm.twitter && $myInfo.comm.twitter == 1}
                                .myTwitter .shareOff { display: none; }
                              {else}
                                .myTwitter .shareOn { display: none; }
                              {/if}
                            </style>
                          </div>
                        </div>
                      </div>
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

    <div class="commList">
      <div class="container">
        <div class="communityList">
          {if $commFollowsInfo|@is_array && $commFollowsInfo|@count gt 0}
            <h4>Estás siguiendo...</h4>
            <div class="row row-eq-height">
              {foreach $commFollowsInfo as $userInfo}
                {* id,login,name,surname,email,description,avatarFileId *}
                <div class="col-sm-12 col-md-6" data-id="{$userInfo.id}">
                  <div class="communityElement">
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="text-center">
                          <div class="socialUser">
                            <div class="commImageUser">
                              <img class="img-fluid" src="{if $userInfo.avatarFileId}/cgmlImg/{$userInfo.avatarFileId}/userPhotoCommunity/{$userInfo.avatarFileId}.jpg{else}/mediaCache/module/rextCommunity/img/user.png{/if}">
                            </div>
                            {if $userInfo.comm}
                              <div class="socialLinks">
                                {if $userInfo.comm.facebook}
                                  <span class="goToFacebook">
                                    <a href="https://www.facebook.com/{$userInfo.comm.facebook}" target="_blank"><i class="fab fa-fw fa-facebook-f" aria-hidden="true"></i></a>
                                  </span>
                                {/if}
                                {if $userInfo.comm.twitter}
                                  <span class="goToTwitter">
                                    <a href="https://twitter.com/{$userInfo.comm.twitter}" target="_blank"><i class="fab fa-fw fa-twitter" aria-hidden="true"></i></a>
                                  </span>
                                {/if}
                              </div>
                            {/if}
                            <div class="followUser">
                              <div class="actionFollow" data-id="{$userInfo.id}" data-follow="0">
                                <button type="button" class="btn showStatus off">{t}Seguir{/t}</button>
                                <button type="button" class="btn showStatus on" style="display: none;">{t}Siguiendo{/t}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-9">
                        <div class="infoUser">
                          <div class="actionFollow" data-id="{$userInfo.id}" data-follow="1">
                            <span class="showStatus off" style="display: none;"><i class="fas fa-user-plus" aria-hidden="true"></i></span>
                            <span class="showStatus on"><i class="fas fa-user-times" aria-hidden="true"></i></span>
                          </div>
                          <div class="commText">
                            <div class="name">{$userInfo.name} {$userInfo.surname}</div>
                          </div>
                          {if $userInfo.favs|@is_array && $userInfo.favs|@count gt 0}
                            <div class="favs">
                              <p>{t}Le gusta...{/t}</p>
                              <div class="row">
                                {foreach $userInfo.favs as $fav}
                                  {if $fav@iteration > 4}
                                    {break}{* abort iterating the array *}
                                  {/if}
                                  <div class="col-xs-3">
                                    <div class="commImage">
                                      <a href="{$fav.url}" title="{$fav.title}">
                                        <img class="img-fluid" src="/cgmlImg/{$fav.image}/userFavsCommunity/{$fav.image}.jpg" alt="{$fav.title}">
                                      </a>
                                    </div>
                                  </div>
                                {/foreach}
                              </div>
                                {if $fav@iteration > 4}
                                  <span class="actionShowAll" data-id="{$userInfo.id}">{t}ver todos{/t}</span>
                                {/if}
                            </div><!-- /favs -->
                          {/if}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              {/foreach}
            </div>
          {else}
            <div class="row">
              <div class="col-xs-12">
                <div class="communityEmpty">
                  <h4>{t}Parece que todavía no has añadido a nadie a tu Comunidad...{/t}</h4>
                </div>
              </div>
            </div>
          {/if}
        </div>
      </div>
    </div>

    {if $commProposeInfo|@is_array && $commProposeInfo|@count gt 0}
      <div class="commPropose">
        <div class="container">
          <div class="communityList">
            <h4>{t}Usuarios similares a tí...{/t}</h4>
            <div class="row row-eq-height">
              {foreach $commProposeList as $userId}
                {$userInfo=$commProposeInfo[$userId]}
                {* id,login,name,surname,email,description,avatarFileId *}
                <div class="col-sm-12 col-md-6" data-id="{$userInfo.id}">
                  <div class="communityElement">
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="text-center">
                          <div class="socialUser">
                            <div class="commImageUser">
                              <img class="img-fluid" src="{if $userInfo.avatarFileId}/cgmlImg/{$userInfo.avatarFileId}/userPhotoCommunity/{$userInfo.avatarFileId}.jpg{else}/mediaCache/module/rextCommunity/img/user.png{/if}">
                            </div>
                            {if $userInfo.comm}
                              <div class="socialLinks">
                                {if $userInfo.comm.facebook}
                                  <span class="goToFacebook">
                                    <a href="https://www.facebook.com/{$userInfo.comm.facebook}" target="_blank"><i class="fab fa-fw fa-facebook-f" aria-hidden="true"></i></a>
                                  </span>
                                {/if}
                                {if $userInfo.comm.twitter}
                                  <span class="goToTwitter">
                                    <a href="https://twitter.com/{$userInfo.comm.twitter}" target="_blank"><i class="fab fa-fw fa-twitter" aria-hidden="true"></i></a>
                                  </span>
                                {/if}
                              </div>
                            {/if}
                            <div class="followUser">
                              <div class="actionFollow" data-id="{$userInfo.id}" data-follow="0">
                                <button type="button" class="btn showStatus off">{t}Seguir{/t}</button>
                                <button type="button" class="btn showStatus on" style="display: none;">{t}Siguiendo{/t}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-9">
                        <div class="infoUser">
                          <div class="actionFollow" data-id="{$userInfo.id}" data-follow="0">
                            <span class="showStatus off"><i class="fas fa-user-plus" aria-hidden="true"></i></span>
                            <span class="showStatus on" style="display: none;"><i class="fas fa-user-times" aria-hidden="true"></i></span>
                          </div>
                          <div class="commText">
                            <div class="name">{$userInfo.name} {$userInfo.surname}</div>
                          </div>
                          {if $userInfo.favs|@is_array && $userInfo.favs|@count gt 0}
                            <div class="favs">
                              <p>{t}Le gusta...{/t}</p>
                              <div class="row">
                                {foreach $userInfo.favs as $fav}
                                  {if $fav@iteration > 4}
                                    {break}{* abort iterating the array *}
                                  {/if}
                                  <div class="col-xs-3">
                                    <div class="commImage">
                                      <a href="{$fav.url}" title="{$fav.title}">
                                        <img class="img-fluid" src="/cgmlImg/{$fav.image}/userFavsCommunity/{$fav.image}.jpg" alt="{$fav.title}">
                                      </a>
                                    </div>
                                  </div>
                                {/foreach}
                              </div>
                                {if $fav@iteration > 4}
                                  <span class="actionShowAll" data-id="{$userInfo.id}">{t}ver todos{/t}</span>
                                {/if}
                            </div><!-- /favs -->
                          {/if}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              {/foreach}
            </div>
          </div>
        </div>
      </div>
    {/if}

    <div class="container commFooter">
    </div>

    <div id="communityFavsModal" class="communityFavsModal modal fade" tabindex="-1" role="dialog" aria-labelledby="communityFavsModal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <img class="iconModal img-fluid" src="{$cogumelo.publicConf.media}/img/iconModal.png">
          </div>
          <div class="modal-body">
            <!-- Contenido de favoritos de cada usuario -->
          </div>
        </div>
      </div>
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
