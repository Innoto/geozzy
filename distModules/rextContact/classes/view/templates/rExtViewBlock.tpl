<!-- rExtViewBlock.tpl en rExtContact module -->

<div class="rExtContact row">

  <div class="col-lg-6">
    <div class="address">
      <i class="fa fa-map-marker"></i>
      {$rExtContact_address|escape:'htmlall'} {$rExtContact_cp|escape:'htmlall'} {$rExtContact_city|escape:'htmlall'} {$rExtContact_province|escape:'htmlall'}
    </div>

    <div class="phone">
      <i class="fa fa-phone"></i>
      {$rExtContact_phone|escape:'htmlall'}
    </div>
  </div>

  <div class="col-lg-6">
    <div class="web">
      <i class="fa fa-globe"></i>
      {$rExtContact_web|escape:'htmlall'}
    </div>

    <div class="email">
      <i class="fa fa-envelope"></i>
      {$rExtContact_email|escape:'htmlall'}
    </div>

    <div class="directions" style="display:none;">
      {$rExtContact_directions|escape:'htmlall'}
    </div>
  </div>
  <!-- taxonomías -->

  <!-- coleciones -->

</div>

<!-- /rExtViewBlock.tpl en rExtContact module -->
