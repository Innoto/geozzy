{if ($rExt.data.activeFb || $rExt.data.activeTwitter || $rExt.data.activeGplus)}

  <ul class="rextSocialNetworkContainer clearfix">
    {if isset($rExt.data.activeFb) && $rExt.data.activeFb}
      <li class="share-net facebook">
        <a class="icon-share" target="_blank" rel="nofollow" href="http://www.facebook.com/sharer.php?u={$cogumelo.publicConf.site_host}{$rExt.data["urlAlias"]}&t={$rExt.data["textFb"]}">
          <i class="fa fa-facebook" aria-hidden="true"></i>
        </a>
      </li>
    {/if}
    {if isset($rExt.data.activeTwitter) && $rExt.data.activeTwitter}
      <li class="share-net twitter">
        <a class="icon-share" target="_blank" rel="nofollow" href="http://twitter.com/share?url={$cogumelo.publicConf.site_host}{$rExt.data["urlAlias"]}&text={$rExt.data["textTwitter"]}">
          <i class="fa fa-twitter" aria-hidden="true"></i>
        </a>
      </li>
    {/if}
    {if isset($rExt.data.activeGplus) && $rExt.data.activeGplus}
      <li class="share-net gplus">
        <a class="icon-share" target="_blank" rel="nofollow" href="https://plus.google.com/share?url={$cogumelo.publicConf.site_host}{$rExt.data["urlAlias"]}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
          <i class="fa fa-google-plus" aria-hidden="true"></i>
        </a>
      </li>
    {/if}
  </ul>

{/if}
