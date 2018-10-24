<!-- rExtViewBlock.tpl en rExtContact module -->

<address class="rExtContact">
  {if (isset($rExt.data.address)&& $rExt.data.address!='')||
      (isset($rExt.data.cp)&& $rExt.data.cp!='') ||
      (isset($rExt.data.city)&& $rExt.data.city!='') ||
      (isset($rExt.data.province)&& $rExt.data.province!='')}
      <div class="col-lg-6">
        <div class="address">
          <i class="fas fa-map-marker-alt icon"></i>
          <div class="data">
            {if $rExt.data.address!==''}
              <p>{$rExt.data.address|escape:'htmlall'}</p>
            {/if}
            {if $rExt.data.cp!=='' || $rExt.data.city!==''}
              <p>{$rExt.data.cp|escape:'htmlall'} {$rExt.data.city|escape:'htmlall'}</p>
            {/if}
            {if $rExt.data.province!==''}
              <p>{$rExt.data.province|escape:'htmlall'}</p>
            {/if}
          </div>
        </div>
      </div>
  {/if}

  {if (isset($rExt.data.phone) && $rExt.data.phone!="")}
  <div class="col-lg-6">

    <div class="phone">
      <i class="fas fa-phone"></i>
      {$rExt.data.phone|escape:'htmlall'}
    </div>
  </div>
  {/if}

  {if (isset($rExt.data.url) && $rExt.data.url!="")}
  <div class="col-lg-6">
    <div class="web">
      <i class="fas fa-globe-americas"></i>
      <a href="{$rExt.data.url|escape:'htmlall'}" target="blank">{$rExt.data.url|escape:'htmlall'}</a>
    </div>
  </div>
  {/if}

  {if (isset($rExt.data.email) && $rExt.data.email!="")}
  <div class="col-lg-6">
    <div class="email">
      <i class="fas fa-envelope"></i>
      {$rExt.data.email|escape:'htmlall'}
    </div>
  </div>
  {/if}

  {if (isset($rExt.data.timetable) && $rExt.data.timetable!="")}
  <div class="col-lg-6">
    <div class="timetable">
      <i class="far fa-clock icon"></i>
      <div class="data">
        {$rExt.data.timetable|nl2br}
      </div>
    </div>
  </div>
  {/if}

  {if (isset($rExt.data.directions) && $rExt.data.directions!="")}
  <div class="directions" style="display:none;">
    {$rExt.data.directions|escape:'htmlall'}
  </div>
  {/if}

  <!-- taxonomías -->

  <!-- coleciones -->

</address>

<!-- /rExtViewBlock.tpl en rExtContact module -->
