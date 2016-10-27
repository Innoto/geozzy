{* id, login, name, surname, email, description, avatarFileId, comm, favList, favs *}
<div class="communityOther" data-id="{$otherUserInfo.id}">
  <div class="row">
    <div class="col-sm-3">
      <div class="commImage">
        <img src="/cgmlImg/{$otherUserInfo.avatarFileId}/fast_cut/{$otherUserInfo.avatarFileId}.jpg">
      </div>
    </div>
    <div class="col-sm-9">
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
    </div>
    <div class="col-sm-12">
      {if $otherUserInfo.favs|@is_array && $otherUserInfo.favs|@count gt 0}
      <div class="favs">
        <p>Favoritos:</p>
        <div class="row">
        {foreach $otherUserInfo.favs as $fav}
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