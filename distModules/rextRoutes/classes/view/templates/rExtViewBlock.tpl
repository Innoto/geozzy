<div class="rExtRoutes">
  <div class="container">
    <div class="row row-eq-height-vertical-centered">
      <div class="col-12 col-md-6 col-lg-4">
        <div class="left-column">
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
              {t}Download route{/t} <i class="fas fa-download"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-4">
        <div class="middle-column">
          <div class="row">
            <div class="col">
              <div class="travelDistance duration">
                <i class="fas fa-map-signs" aria-hidden="true"></i> {$rExt.data.travelDistance/1000} Km
              </div>
            </div>
            <div class="col">
              <div class="travelTime duration">
                <i class="far fa-clock" aria-hidden="true"></i> {$rExt.data.durationHours}h {$rExt.data.durationMinutes}min
              </div>
            </div>
          </div>

          <div class="row circular">
            {if $rExt.data.circular}
              <div class="col">
                <div class="title">
                  <div class="text">{t}Circular route{/t}</div>
                </div>
              </div>
              <div class="col">
                <div class="icon">
                  <img class="img-fluid" alt="{t}circular{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/circular.png">
                </div>
              </div>
            {else}
              <div class="col">
                <div class="title">
                  <div class="text">{t}Lineal route{/t}</div>
                </div>
              </div>
              <div class="col">
                <div class="icon">
                  <img class="img-fluid" alt="{t}lineal{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/lineal.png">
                </div>
              </div>
            {/if}
          </div>

          <div class="row slope">
            <div class="col-12 col-md-4 col-lg-4">
              <div class="title">
                <div class="text">{t}Slopes{/t}</div>
              </div>
            </div>

            <div class="col-6 col-md-4 col-lg-4">
              <div class="slope-up icon">
                <img class="img-fluid" alt="{t}slope up{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/ascendente.png">
                <div class="text">{$rExt.data.slopeUp} m.</div>
              </div>
            </div>

            <div class="col-6 col-md-4 col-lg-4">
              <div class="slope-down icon">
                <img class="img-fluid" alt="{t}slope down{/t}" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/descendente.png">
                <div class="text">{$rExt.data.slopeDown} m.</div>
              </div>
            </div>
          </div>
        </div><!--middle-column-->
      </div>

      <div class="col-12 col-md-6 col-lg-4">
        <div class="right-column">
          <div class="row bar environment">
            <div class="col-4 col-md-6 col-lg-6">
              <div class="title">{t}Environment{/t}</div>
            </div>
            <div class="col-8 col-md-6 col-lg-6">
              <div class="squares">
                <div class="barraEsfuerzo ruta_{$rExt.data.difficultyEnvironment}"></div>
              </div>
            </div>
          </div>

          <div class="row bar itinerary">
            <div class="col-4 col-md-6 col-lg-6">
              <div class="title">{t}Itinerary{/t}</div>
            </div>
            <div class="col-8 col-md-6 col-lg-6">
              <div class="squares">
                <div class="barraEsfuerzo ruta_{$rExt.data.difficultyItinerary}"></div>
              </div>
            </div>
          </div>

          <div class="row bar displacement">
            <div class="col-4 col-md-6 col-lg-6">
              <div class="title">{t}Displacement{/t}</div>
            </div>
            <div class="col-8 col-md-6 col-lg-6">
              <div class="squares">
                <div class="barraEsfuerzo ruta_{$rExt.data.difficultyDisplacement}"></div>
              </div>
            </div>
          </div>

          <div class="row bar effort">
            <div class="col-4 col-md-6 col-lg-6">
              <div class="title">{t}Effort{/t}</div>
            </div>
            <div class="col-8 col-md-6 col-lg-6">
              <div class="squares">
                <div class="barraEsfuerzo ruta_{$rExt.data.difficultyEffort}"></div>
              </div>
            </div>
          </div>

        </div><!-- right-column-->
      </div>
    </div> <!--row-->
  </div>
</div>

<style>
  .resourceRouteGraphLegend {
    position:absolute;
    top:15px;
    right:30px;
    color:#fff;
    font-weight:bold;
  }

  .dygraph-legend {

    display:none;
    /*box-shadow: 4px 4px 4px #888;*/
  }
  .resourceRouteGraphContainer {
    border-radius: 4px;
    cursor:pointer;
    margin-bottom:22px;
    background-color:rgba(32, 32, 32, 0.7 );
  }

    .resourceRouteGraphContainer .resourceRouteGraph {
      width:300px;
      height:80px;

      margin-right:20px;
      margin-top:30px;
      margin-bottom:10px;
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
