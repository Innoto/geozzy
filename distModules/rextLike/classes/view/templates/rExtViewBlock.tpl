<!-- rExtViewBlock.tpl en rExtLike module -->

<style type="text/css">
.rExtLike,
.rExtLike .like-off,
.rExtLike .like-on { color: #00cc00; }
.rExtLike .like-off { display: inline; }
.rExtLike .like-on { display: none; }
.rExtLike.selected .like-off { display: none; }
.rExtLike.selected .like-on { display: inline; }
.rExtLike:hover,
.rExtLike:hover .like-off,
.rExtLike:hover .like-on { color: #0000cc; }
</style>

{if isset($rExt.data) && $rExt.data!==false}
  {assign var="likeStatus" value=1}
{else}
  {assign var="likeStatus" value=0}
{/if}

<div class="rExtLike{if $likeStatus==1} selected{/if}" data-like-resource="{$resId}" data-like-status="{$likeStatus}">
  <span class="fa fa-heart-o like-off"></span>
  <span class="fa fa-heart like-on"></span>
  <!--
  {* var_dump($rExt.data) *}
  -->
</div>

<!-- /rExtViewBlock.tpl en rExtLike module -->
