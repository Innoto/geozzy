{* id, login, name, surname, email, description, avatarFileId, comm, favList, favs *}
<div class="communityOther" data-id="{$otherUserInfo.id}">
  <div class="row">
    <!-- <div class="col-sm-3"> -->
    <div class="col-sm-12">
      <div class="user text-center">
        <div class="commImage">
          <img class="img-fluid" src="/cgmlImg/{$otherUserInfo.avatarFileId}/userPhotoCommunity/{$otherUserInfo.avatarFileId}.jpg">
        </div>
        <div class="commText">
          <div class="name">{$otherUserInfo.name} {$otherUserInfo.surname}</div>
        </div>
      </div>
    </div>
    <!-- <div class="col-sm-9">
      {if $otherUserInfo.comm}
        <div class="socialLinks">
          {if $otherUserInfo.comm.facebook}
            <a href="https://www.facebook.com/{$otherUserInfo.comm.facebook}" target="_blank">Facebook</a>
          {/if}
          {if $otherUserInfo.comm.twitter}
            <a href="https://twitter.com/{$otherUserInfo.comm.twitter}" target="_blank">Twitter</a>
          {/if}
        </div>
      {/if}
    </div> -->
    <div class="col-sm-12">
      {if $otherUserInfo.favs|@is_array && $otherUserInfo.favs|@count gt 0}
        <div class="favs">
          <h3>{t}Favoritos:{/t}</h3>
          <div class="row">
            {foreach $otherUserInfo.favs as $fav}
              <div class="col-sm-2">
                <div class="commImage">
                  <a href="{$fav.url}" title="{$fav.title}">
                    <img class="img-fluid" src="{if $fav.image}/cgmlImg/{$fav.image}/userFavsCommunity/{$fav.image}.jpg{else}/mediaCache/module/rextCommunity/img/user.png{/if}" alt="{$fav.title}">
                  </a>
                </div>
              </div>
            {/foreach}
          </div>
        </div><!-- /favs -->
      {/if}
    </div>
  </div>
</div>
