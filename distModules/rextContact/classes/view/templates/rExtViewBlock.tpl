<!-- rExtViewBlock.tpl en rExtContact module -->

<div class="rExtContact row">

  <div class="col-lg-6">
    {if $rExt.data.address || $rExt.data.cp || $rExt.data.city || $rExt.data.province}
    <div class="address">
      <i class="fa fa-map-marker"></i>
      {$rExt.data.address|escape:'htmlall'} {$rExt.data.cp|escape:'htmlall'} {$rExt.data.city|escape:'htmlall'} {$rExt.data.province|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="col-lg-6">
    {if $rExt.data.phone}
    <div class="phone">
      <i class="fa fa-phone"></i>
      {$rExt.data.phone|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="col-lg-6">
    {if $rExt.data.web}
    <div class="web">
      <i class="fa fa-globe"></i>
      {$rExt.data.web|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="col-lg-6">
    {if $rExt.data.email}
    <div class="email">
      <i class="fa fa-envelope"></i>
      {$rExt.data.email|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="col-lg-6">
    {if $rExt.data.timetable}
    <div class="timetable">
      <i class="fa fa-clock-o"></i>
      {$rExt.data.timetable|escape:'htmlall'}
    </div>
    {/if}
  </div>

  <div class="directions" style="display:none;">
    {$rExt.data.directions|escape:'htmlall'}
  </div>

  <!-- taxonomías -->

  <!-- coleciones -->

</div>

<!-- /rExtViewBlock.tpl en rExtContact module -->
