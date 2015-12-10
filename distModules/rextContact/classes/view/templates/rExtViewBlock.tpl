<!-- rExtViewBlock.tpl en rExtContact module -->

<address class="rExtContact row">

  {if isset($rExt.data.address) || isset($rExt.data.cp) || isset($rExt.data.city) || isset($rExt.data.province)}
  <div class="col-lg-6">
    <div class="address">
      <i class="fa fa-map-marker icon"></i>
      <div class="data">
      {$rExt.data.address|escape:'htmlall'} <br>{$rExt.data.cp|escape:'htmlall'} {$rExt.data.city|escape:'htmlall'} <br>{$rExt.data.province|escape:'htmlall'}
      </div>
    </div>
  </div>
  {/if}

  {if isset($rExt.data.phone) && $rExt.data.phone!=""}
  <div class="col-lg-6">

    <div class="phone">
      <i class="fa fa-phone"></i>
      {$rExt.data.phone|escape:'htmlall'}
    </div>
  </div>
  {/if}

  {if isset($rExt.data.url) && $rExt.data.url!=""}
  <div class="col-lg-6">
    <div class="web">
      <i class="fa fa-globe"></i>
      {$rExt.data.url|escape:'htmlall'}
    </div>
  </div>
  {/if}

  {if isset($rExt.data.email) && $rExt.data.email!=""}
  <div class="col-lg-6">
    <div class="email">
      <i class="fa fa-envelope"></i>
      {$rExt.data.email|escape:'htmlall'}
    </div>
  </div>
  {/if}

  {if isset($rExt.data.timetable) && $rExt.data.timetable!=""}
  <div class="col-lg-6">
    <div class="timetable">
      <i class="fa fa-clock-o icon"></i>
      <div class="data">
        {$rExt.data.timetable|escape:'htmlall'}
      </div>
    </div>
  </div>
  {/if}

  {if isset($rExt.data.directions) && $rExt.data.directions!=""}
  <div class="directions" style="display:none;">
    {$rExt.data.directions|escape:'htmlall'}
  </div>
  {/if}

  <!-- taxonomías -->

  <!-- coleciones -->

</address>

<!-- /rExtViewBlock.tpl en rExtContact module -->
