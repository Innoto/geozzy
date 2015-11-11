<!-- rExtViewBlock.tpl en rExtContact module -->

<div class="rExtContact row">

  <div class="col-lg-6">
    {if $rExtContact_address || $rExtContact_cp || $rExtContact_city || $rExtContact_province}
    <div class="address">
      <i class="fa fa-map-marker"></i>
      {$rExtContact_address|escape:'htmlall'} {$rExtContact_cp|escape:'htmlall'} {$rExtContact_city|escape:'htmlall'} {$rExtContact_province|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="col-lg-6">
    {if $rExtContact_phone}
    <div class="phone">
      <i class="fa fa-phone"></i>
      {$rExtContact_phone|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="col-lg-6">
    {if $rExtContact_web}
    <div class="web">
      <i class="fa fa-globe"></i>
      {$rExtContact_web|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="col-lg-6">
    {if $rExtContact_email}
    <div class="email">
      <i class="fa fa-envelope"></i>
      {$rExtContact_email|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="col-lg-6">
    {if $rExtContact_timetable}
    <div class="timetable">
      <i class="fa fa-clock-o"></i>
      {$rExtContact_timetable|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="directions" style="display:none;">
    {$rExtContact_directions|escape:'htmlall'}
  </div>

  <!-- taxonomías -->

  <!-- coleciones -->

</div>

<!-- /rExtViewBlock.tpl en rExtContact module -->
