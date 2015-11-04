<!-- rExtViewBlock.tpl en rExtContact module -->

<div class="rExtContact">

    <div class="address">
      <label>{t}Address{/t}</label>
      <i class="fa fa-map-marker"></i>
      {$rExtContact_address|escape:'htmlall'} {$rExtContact_cp|escape:'htmlall'} {$rExtContact_city|escape:'htmlall'}
    </div>

    <div class="province">
      <label>{t}Province{/t}</label>
      <i class="fa fa-globe"></i>
      {$rExtContact_province|escape:'htmlall'}
    </div>

    <div class="email">
      <label>{t}contact e-mail{/t}</label>
      <i class="fa fa-envelope"></i>
      {$rExtContact_email|escape:'htmlall'}
    </div>

    <div class="directions" style="display:none;">
      <label>{t}How to arrive{/t}</label>
      {$rExtContact_directions|escape:'htmlall'}
    </div>
  <!-- taxonomías -->

  <!-- coleciones -->

</div>

<!-- /rExtViewBlock.tpl en rExtContact module -->
