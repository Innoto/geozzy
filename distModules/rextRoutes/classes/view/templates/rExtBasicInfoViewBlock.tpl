<div class="rExtRoutesBasic">
  <div class="routeInfo">

    <div class="itemRoute routeStart">
      <div class="icon">
        <img class="img-fluid" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/markerStart.png" alt="{t}Route start{/t}">
      </div>
      <div class="text">{$rExt.data.routeStart}</div>
    </div>

    <div class="itemRoute routeEnd">
      <div class="icon">
        <img class="img-fluid" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/markerEnd.png" alt="{t}Route end{/t}">
      </div>
      <div class="text">{$rExt.data.routeEnd}</div>
    </div>

    <div class="itemRoute routeTravelDistance duration">
      <div class="icon">
        <i class="fas fa-map-signs fa-fw"></i>
      </div>
      <div class="text">{$rExt.data.travelDistance/1000} Km</div>
    </div>

    <div class="itemRoute routeTravelTime duration">
      <div class="icon">
        <i class="far fa-clock fa-fw"></i>
      </div>
      <div class="text">
        {if $rExt.data.durationHours !== '0'}
          <span>{$rExt.data.durationHours} h</span>
        {/if}
        {if $rExt.data.durationMinutes !== '00'}
          <span>{$rExt.data.durationMinutes} min</span>
        {/if}
      </div>
    </div>

    <div class="itemRoute routeType">
      <div class="title">{if $rExt.data.circular}{t}Circular route{/t}{else}{t}Lineal route{/t}{/if}</div>
      <div class="icon {if $rExt.data.circular}circular{else}lineal{/if}">
        {if $rExt.data.circular}
          <img class="img-fluid" alt="{t}circular{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/circular.png">
        {else}
          <img class="img-fluid" alt="{t}lineal{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/lineal.png">
        {/if}
      </div>
    </div>

    <div class="itemRoute routeSlope">
      <div class="title">{t}Slopes{/t}</div>
      <div class="slope slope-up">
        <div class="text">{$rExt.data.slopeUp} m.</div>
        <div class="icon">
          <img class="img-fluid" alt="{t}slope up{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/ascendente.png">
        </div>
      </div>
      <div class="slope slope-down">
        <div class="text">{$rExt.data.slopeDown} m.</div>
        <div class="icon">
          <img class="img-fluid" alt="{t}slope down{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/descendente.png">
        </div>
      </div>
    </div>

    <div class="itemRoute routeBar">
      <div class="bar environment">
        <div class="title">{t}Environment{/t}</div>
        <div class="squares">
          <div class="barraEsfuerzo ruta_{$rExt.data.difficultyEnvironment}"></div>
        </div>
      </div>
      <div class="bar itinerary">
        <div class="title">{t}Itinerary{/t}</div>
        <div class="squares">
          <div class="barraEsfuerzo ruta_{$rExt.data.difficultyItinerary}"></div>
        </div>
      </div>
      <div class="bar displacement">
        <div class="title">{t}Displacement{/t}</div>
        <div class="squares">
          <div class="barraEsfuerzo ruta_{$rExt.data.difficultyDisplacement}"></div>
        </div>
      </div>
      <div class="bar effort">
        <div class="title">{t}Effort{/t}</div>
        <div class="squares">
          <div class="barraEsfuerzo ruta_{$rExt.data.difficultyEffort}"></div>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
  .resourceRouteGraphLegend{
    position: absolute;
    top: 15px;
    right: 30px;
    color: #fff;
    font-weight: bold;
  }

  .dygraph-legend{
    display: none;
    /*box-shadow: 4px 4px 4px #888;*/
  }

  .resourceRouteGraphContainer{
    border-radius: 4px;
    cursor: pointer;
    margin-bottom: 22px;
    background-color: rgba(32, 32, 32, 0.7 );
  }

  .resourceRouteGraphContainer .resourceRouteGraph{
    width: 300px;
    height: 80px;
    margin-right: 20px;
    margin-top: 30px;
    margin-bottom: 10px;
  }
</style>


<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtRoutesOptions = {
    resourceId: {$rExt.data.resourceId},
    showGraph: true,
    graphContainer:false
  };
</script>