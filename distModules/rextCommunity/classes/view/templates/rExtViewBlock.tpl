<!-- rExtViewBlock.tpl en rExtCommunity module -->

<style type="text/css">
.rExtCommunity,
.rExtCommunity .comm-off,
.rExtCommunity .comm-on { color: #00cc00; }
.rExtCommunity .comm-off { display: inline; }
.rExtCommunity .comm-on { display: none; }
.rExtCommunity.selected .comm-off { display: none; }
.rExtCommunity.selected .comm-on { display: inline; }
.rExtCommunity:hover,
.rExtCommunity:hover .comm-off,
.rExtCommunity:hover .comm-on { color: #0000cc; }
</style>

{if isset($rExt.data) && $rExt.data!==false}
  {assign var="commStatus" value=1}
{else}
  {assign var="commStatus" value=0}
{/if}

<div class="rExtCommunity{if $commStatus==1} selected{/if}" data-community-resource="{$resId}" data-community-status="{$commStatus}">
  <i class="far fa-heart comm-off"></i>
  <i class="fas fa-heart comm-on"></i>
  <!--
  {var_dump($rExt.data)}
  -->
</div>

<!-- /rExtViewBlock.tpl en rExtCommunity module -->
