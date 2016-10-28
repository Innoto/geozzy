<!-- rTypeViewBlock.tpl en rTypeCommunity module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-responsive" alt="Tu Comunidad" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png">
          <h1>{t}Tu Comunidad{/t}</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="communitySec gzSection">
    <div class="container commHeader">
      <h3>{t}Mi perfil{/t}</h3>
      <div class="row">
        <div class="myInfo" data-id="{$myInfo.id}">
          <div class="col-sm-5 col-md-4">
            <div class="infoPersonal text-center">
              <div class="commImage">
                <img src="/cgmlImg/{$myInfo.avatarFileId}/fast_cut/{$myInfo.avatarFileId}.jpg">
              </div>
              <!-- <div class="login">{$myInfo.login}</div> -->
              <!-- <a href="/userprofile#user/profile"><i class="fa fa-2x fa-pencil-square" aria-hidden="true"></i> {t}Editar mi perfil{/t}</a> -->
            </div>
          </div>
          <div class="col-sm-7 col-md-8">
            <div class="commText">
              <!-- <div class="name">{$myInfo.name} {$myInfo.surname}</div> -->
              <div class="row">
                <div class="commRS">
                  <div class="col-md-6">
                    <!-- <div class="col-sm-9 col-md-9"> -->
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="myShare">
                          <span class="view">
                            <span class="shareOn"><i class="fa fa-lg fa-check" aria-hidden="true"></i></span>
                            <span class="shareOff"><i class="fa fa-lg fa-times" aria-hidden="true"></i></span>
                            <style type="text/css">
                              {if $myInfo.comm.share && $myInfo.comm.share == 1}
                              .myShare .shareOff { display: none; }
                              {else}
                              .myShare .shareOn { display: none; }
                              {/if}
                            </style>
                          </span>
                          <span class="textShare">{t}Compartir mis datos con la comunidad{/t}</span>
                          <span class="edit">
                            <label>
                              <input type="radio" name="shareStatus" class="shareStatusOn"
                              value="1"{if $myInfo.comm.share && $myInfo.comm.share == 1} checked{/if}>
                              <i class="fa fa-lg fa-check" aria-hidden="true"></i>
                            </label>
                            <label>
                              <input type="radio" name="shareStatus" class="shareStatusOff"
                              value="0"{if !$myInfo.comm.share || $myInfo.comm.share != 1} checked{/if}>
                              <i class="fa fa-lg fa-times" aria-hidden="true"></i>
                            </label>
                          </span>
                        </div>
                      </div>
                      <!-- <div class="col-sm-3 col-md-3"> -->
                      <div class="col-xs-12">
                        <div class="actions">
                          <span class="view actionEdit"><i class="fa fa-lg fa-pencil" aria-hidden="true"></i> {t}Editar{/t}</span>
                          <span class="edit actionSave"><i class="fa fa-lg fa-floppy-o" aria-hidden="true"></i> {t}Guardar{/t}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <!-- <div class="col-sm-12 col-md-6"> -->
                      <div class="rs">
                        <div class="col-xs-12">
                          <!-- <div class="myFacebook">
                            <span class="iconRs rsFacebook"><i class="fa fa-fw fa-3x fa-facebook" aria-hidden="true"></i></span>
                            <span class="view facebookAccount">{$myInfo.comm.facebook|default:'Enlazar con Facebook'}</span>
                            <span class="edit">
                              <input type="text" name="facebookAccount" value="{$myInfo.comm.facebook|default:''}">
                            </span>
                          </div> -->
                          <div class="myFacebook noShare">
                            <span class="iconRs rsFacebook"><i class="fa fa-fw fa-3x fa-facebook" aria-hidden="true"></i></span>
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
                        <!-- <div class="col-sm-12 col-md-6"> -->
                        <div class="col-xs-12">
                          <!-- <div class="myTwitter">
                            <span class="iconRs rsTwitter"><i class="fa fa-fw fa-3x fa-twitter" aria-hidden="true"></i></span>
                            <span class="view twitterAccount">{$myInfo.comm.twitter|default:'Enlazar con Twitter'}</span>
                            <span class="edit">
                              <input type="text" name="twitterAccount" value="{$myInfo.comm.twitter|default:''}">
                            </span>
                          </div> -->
                          <div class="myTwitter noShare">
                            <span class="iconRs rsTwitter"><i class="fa fa-fw fa-3x fa-twitter" aria-hidden="true"></i></span>
                            <div class="view">
                              <span class="view shareOn">{t}Perfil enlazado con Twitter{/t}</span>
                              <span class="view shareOff">{t}Enlazar mi perfil con Twitter{/t}</span>
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
                  {if $fav@iteration > 6}
                    <span class="actionShowAll" data-id="{$userInfo.id}">Ver todo!!!</span>
                  {/if}
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

    <div id="communityFavsModal" class="communityFavsModal modal fade" tabindex="-1" role="dialog" aria-labelledby="communityFavsModal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <img class="iconModal img-responsive" src="{$cogumelo.publicConf.media}/img/iconModal.png">
          </div>
          <div class="modal-body">

            <!-- Contenido de favoritos de cada usuario -->
            <p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p>
            <p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p>
            <p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p>
            <p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p>
            <p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p>
            <p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p>
            <p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p>
            <p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p>
            <p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p><p>Ola meu</p>

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
