<div class="rExtRoutes container">
  <div class="left-column col-md-4 col-sd-4">
    <div class="route">
      <div class="routeStart">
        <i class="icon fa fa-caret-right" aria-hidden="true"></i>
        {$rExt.data.routeStart}
      </div>
      <div class="routeEnd">
        <i class="icon fa fa-flag-checkered" aria-hidden="true"></i>
        {$rExt.data.routeEnd}
      </div>
      <div class="routeFile">
        <a href="{$cogumelo.publicConf.mediaHost}cgmlformfilewd/{$rExt.data.routeFile.id}/{$rExt.data.routeFile.originalName}"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i>Descargar ruta</a>
      </div>
  </div>
  </div>
  <div class="middle-column col-md-4 col-sd-4">
    <div class="itinerary row">
      <div class="box col-md-4 col-sd-4">{$rExt.data.travelDistance} Km</div>
      <div class="box col-md-4 col-sd-4">{$rExt.data.durationMinutes} h.</div>
      <div class="box circular col-md-4 col-sd-4">
        {if $rExt.data.circular}
          <i class="icon fa fa-repeat" aria-hidden="true"></i>
          <p>circular</p>
        {/if}
      </div>
    </div>
    <hr class="separador"/>
    <div class="slope row">
      <div class="title">{t}Slopes{/t}</div>
      <div class="col-md-6 col-sd-6">
        <i class="slope-up fa fa-arrow-circle-up" aria-hidden="true"></i>
        {$rExt.data.slopeUp} m.
      </div>
      <div class="col-md-6 col-sd-6">
        <i class="slope-down fa fa-arrow-circle-down" aria-hidden="true"></i>
        {$rExt.data.slopeDown} m.
      </div>
    </div>
  </div>
  <div class="right-column col-md-4 col-sd-4">
    <div class="environment">
      {t}Environment{/t}
      {$rExt.data.difficultyEnvironment}
    </div>
    <div class="itinerary">
      {t}Itinerary{/t}
      {$rExt.data.difficultyItinerary}
    </div>
    <div class="displacement">
      {t}Displacement{/t}
      {$rExt.data.difficultyDisplacement}
    </div>
    <div class="effort">
      {t}Effort{/t}
      {$rExt.data.difficultyEffort}
    </div>  
  </div>
</div>
