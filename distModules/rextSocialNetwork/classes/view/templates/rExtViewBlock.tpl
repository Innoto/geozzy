{if ($rExt.data.activeFb || $rExt.data.activeTwitter || $rExt.data.activeGplus)}

  <ul class="rextSocialNetworkContainer clearfix">
    {if isset($rExt.data.activeFb) && $rExt.data.activeFb}
      <li class="share-net facebook">
        <a class="icon-share" target="_blank" rel="nofollow" href="http://www.facebook.com/sharer.php?u={$rExt.data.url}&t={$rExt.data.textFb|escape:'url'}">
          <i class="fab fa-facebook-f" aria-hidden="true"></i>
        </a>
      </li>
    {/if}
    {if isset($rExt.data.activeTwitter) && $rExt.data.activeTwitter}
      <li class="share-net twitter">
        <a class="icon-share" target="_blank" rel="nofollow" href="http://twitter.com/share?url={$rExt.data.url}&text={$rExt.data.textTwitter|escape:'url'}{if isset($cogumelo.publicConf.socialNetworks.twitter)}&via={$cogumelo.publicConf.socialNetworks.twitter}{/if}">
          <i class="fab fa-twitter" aria-hidden="true"></i>
        </a>
      </li>
    {/if}
    {*if isset($rExt.data.activeGplus) && $rExt.data.activeGplus}
      <li class="share-net gplus">
        <a class="icon-share" target="_blank" rel="nofollow" href="https://plus.google.com/share?url={$rExt.data.url}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=420');return false;">
          <i class="fab fa-google-plus-g" aria-hidden="true"></i>
        </a>
      </li>
    {/if*}
  </ul>

{/if}
