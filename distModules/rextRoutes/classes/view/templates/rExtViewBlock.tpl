<div class="rExtRoutes container">
  <div class="left-column col-md-4 col-sd-4">
    <div class="route">
      <div class="routeStart">
        <div class="icon">
          {$rExt.data.routeStart}
        </div>
      </div>
      <div class="routeEnd">
        <div class="icon">
          {$rExt.data.routeEnd}
        </div>
      </div>
    </div>
    <div class="routeFile">
      <a class="btn" href="{$cogumelo.publicConf.mediaHost}cgmlformfilewd/{$rExt.data.routeFile.id}/{$rExt.data.routeFile.originalName}">
        {t}Download route{/t} <i class="fa fa-download" aria-hidden="true"></i>
      </a>
    </div>
  </div>

  <div class="middle-column col-md-4 col-sd-4">

    <div class="duration row">
      <div class="travelDistance col-md-6 col-sd-6">{$rExt.data.travelDistance} Km</div>
      <div class="travelTime col-md-6 col-sd-6">{$rExt.data.durationHours}h {$rExt.data.durationMinutes}min</div>
    </div>

    <div class="circular row">
      {if $rExt.data.circular}
        <div class="title col-md-6 col-sd-6">
          <div class="text">{t}Circular route{/t}</div>
        </div>
        <div class="icon col-md-4 col-sd-4">
          <img class="img-responsive" alt="{t}circular{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/circular.png"></img>
        </div>
      {else}
        <div class="title col-md-6 col-sd-6">
          <div class="text">{t}Circular route{/t}</div>
        </div>
        <div class="icon col-md-4 col-sd-4">
          <img class="img-responsive" alt="{t}lineal{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/lineal.png"></img>
        </div>
      {/if}
    </div>

    <div class="slope row">
      <div class="title col-md-4 col-sd-4">
        <div class="text">{t}Slopes{/t}</div>
      </div>
      <div class="slope-up icon col-md-4 col-sd-4">
        <img class="img-responsive" alt="{t}slope up{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/ascendente.png"></img>
        <div class="text">{$rExt.data.slopeUp} m.</div>
      </div>
      <div class="slope-down icon col-md-4 col-sd-4">
        <img class="img-responsive" alt="{t}slope down{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/descendente.png"></img>
        <div class="text">{$rExt.data.slopeDown} m.</div>
      </div>
    </div>

  </div>

  <div class="right-column col-md-4 col-sd-4">
    <div class="bar environment">
      <div class="title col-md-6 col-sd-6">{t}Environment{/t}</div>
      <div class="squares col-md-6 col-sd-6">
        <div class="barraEsfuerzo ruta_{$rExt.data.difficultyEnvironment}"></div>
      </div>
    </div>
    <div class="bar itinerary">
      <div class="title col-md-6 col-sd-6">{t}Itinerary{/t}</div>
      <div class="squares col-md-6 col-sd-6">
        <div class="barraEsfuerzo ruta_{$rExt.data.difficultyItinerary}"></div>
      </div>
    </div>
    <div class="bar displacement">
      <div class="title col-md-6 col-sd-6">{t}Displacement{/t}</div>
      <div class="squares col-md-6 col-sd-6">
        <div class="barraEsfuerzo ruta_{$rExt.data.difficultyDisplacement}"></div>
      </div>
    </div>
    <div class="bar effort">
      <div class="title col-md-6 col-sd-6">{t}Effort{/t}</div>
      <div class="squares col-md-6 col-sd-6">
        <div class="barraEsfuerzo ruta_{$rExt.data.difficultyEffort}"></div>
      </div>
    </div>
  </div>
</div>

<style>
  .resourceRouteGraphContainer {
    border-radius: 4px;
    cursor:pointer;
    margin-bottom:22px;
    background-color:rgba(32, 32, 32, 0.7 );
  }

    .resourceRouteGraphContainer .resourceRouteGraph {
      width:300px;
      height:100px;

      margin-right:20px;
      margin-top:30px;
      margin-bottom:10px;
    }

</style>


<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtRoutesOptions = {
    resourceId: {$rExt.data.resourceId}
  };
</script>
