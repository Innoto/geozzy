<!-- rExtViewBlock.tpl en rExtFavourite module -->

<style type="text/css">
.rExtFavourite { color: #00cc00; }
.rExtFavourite .fav-off { display: inline; }
.rExtFavourite .fav-on { display: none; }
.rExtFavourite:hover .fav-off { display: none; }
.rExtFavourite:hover .fav-on { display: inline; }
.rExtFavourite.selected .fav-off { display: none; }
.rExtFavourite.selected .fav-on { display: inline; }
.rExtFavourite.selected:hover .fav-off { display: inline; }
.rExtFavourite.selected:hover .fav-on { display: none; }
</style>

{if isset($rExt.data) && $rExt.data!==false}
  {assign var="favStatus" value=1}
{else}
  {assign var="favStatus" value=0}
{/if}

<div class="rExtFavourite{if $favStatus==1} selected{/if}" data-favourite-resource="{$resId}" data-favourite-status="{$favStatus}">
  <i class="fa fa-heart-o fav-off"></i>
  <i class="fa fa-heart fav-on"></i>
  <!--
  {var_dump($rExt.data)}
  -->
</div>

<!-- /rExtViewBlock.tpl en rExtFavourite module -->
