<!-- rExtViewBlock.tpl en rExtMatterport module -->
<style>
  .rExtMatterport {
    padding: 0;
  }
  .rExtMatterport .matterport-showcase {
    position: relative;
    padding-bottom: 80vh;
    height: 0;
    overflow: hidden;
  }
  .rExtMatterport .matterport-showcase iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
</style>
<div class="rExtMatterport">
  <div class="matterport-showcase">
    <iframe width="853" height="480"
      src="https://my.matterport.com/show/?m={$rExt.data.idModel}&lp={$rExt.data.looped}{if $rExt.data.autostart !== 0}&ts={$rExt.data.autostart}{/if}&play={$rExt.data.autoload}&wh={$rExt.data.enableScrollWheel}"
      frameborder="0" allowfullscreen allowvr></iframe>
  </div>
</div>
<!-- /rExtViewBlock.tpl en rExtMatterport module -->
