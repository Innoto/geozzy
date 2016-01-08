<!-- rExtViewBlock.tpl en rExtSocial module -->

<p> --- rExtViewBlock.tpl en rExtFile module</p>
UHHHHHHHHHHHHHHHHH
<div class="rExtSocial">

  Modulo RRSS
  {if isset( $rExt.data )}
  <div class="socialNet">
    <label>{t}Enable Facebook{/t}</label>
    {$rExt.data.activeFb}
  </div>
  <div class="socialNet">
    <label>{t}Text for facebook{/t}</label>
    {$rExt.data.textfb}
  </div>
  {/if}

</div>

<!-- /rExtViewBlock.tpl en rExtSocial module -->
