<!-- rExtViewBlock.tpl en rExtFavourite module -->

<style type="text/css">
.rExtFavourite,
.rExtFavourite .fav-off,
.rExtFavourite .fav-on { color: #00cc00; }
.rExtFavourite .fav-off { display: inline; }
.rExtFavourite .fav-on { display: none; }
.rExtFavourite.selected .fav-off { display: none; }
.rExtFavourite.selected .fav-on { display: inline; }
.rExtFavourite:hover,
.rExtFavourite:hover .fav-off,
.rExtFavourite:hover .fav-on { color: #0000cc; }
</style>

{if isset($rExt.data) && $rExt.data!==false}
  {assign var="favStatus" value=1}
{else}
  {assign var="favStatus" value=0}
{/if}

<div class="rExtFavourite{if $favStatus==1} selected{/if}" data-favourite-resource="{$resId}" data-favourite-status="{$favStatus}">
  <span class="far fa-heart fav-off"></span>
  <span class="fas fa-heart fav-on"></span>
  <!--
  {* var_dump($rExt.data) *}
  -->
</div>

<!-- /rExtViewBlock.tpl en rExtFavourite module -->
