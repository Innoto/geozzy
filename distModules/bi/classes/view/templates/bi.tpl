<section id="statisticsModule" >
  <div id="statisticsHeader" class="headSection clearfix">
    {block name="headSection"}

    <div class="row">
      <div class="col-lg-6 col-md-12 clearfix">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars"></i>
        </button>
        <div class="headerTitleContainer">
          <h3>Estadísticas / Comportamiendo usuarios</h3>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 clearfix">
        <div class="headerActionsContainer">
          <button class="btn btn-default" id="newAnalysis">Nuevo Análisis</button>
        </div>
      </div>
    </div>
    {/block}


    <!-- /.navbar-header -->
  </div><!-- /headSection -->


  <div class="contentSection clearfix">
    <div class="row">
      <section id="statisticsPanel" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
          <div class="panel panel-success">
              <div class="panel-heading">
                  <h4 class="panel-title">Estadísticas</h4>
              </div>
              <div class="panel-body">
                  <div id="statsParams">
                      <div id="metricsSelect"></div>
                      <div id="orgsSelect"></div>
                      <div id="detailBySelect"></div>
                  </div>
                  <div id="result">
                      <div id="loading" class="col-xs-offset-5 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
                          <div class="mdl-spinner mdl-js-spinner is-active"></div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <section id="statisticsFiltersPanel" class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="panel-title">Fuentes de datos</h4>
                </div>
                <div class="panel-body">
                    <div id="filtersSelector">
                    </div>
                    <div id="filters">
                        <div id="mapFilter"></div>
                    </div>
                    <div id="applyButton">
                        <div class="row">
                            <hr>
                        </div>
                        <button class="btn btn-default" id="applyFilters">Aplicar Filtros</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
  </div><!-- /contentSection -->

</section>
<script data-main="/media/module/bi/js/main" src="/vendor/bower/requirejs/require.js"></script>
