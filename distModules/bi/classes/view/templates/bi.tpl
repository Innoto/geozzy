
<section id="statisticsModule" >
    <div class="container">
        <section id="statisticsHeader">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><i class="fa fa-gear fa-lg"></i> Estadísticas / Comportamiendo usuarios</h3>
                    <button class="btn btn-default pull-right" id="newAnalysis">Nuevo Análisis</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </section>
        <section id="statisticsPanel" class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
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
                        <div id ="loading" class="col-xs-offset-5 col-sm-offset-5 col-md-offset-5 col-lg-offset-5">
                            <div class="mdl-spinner mdl-js-spinner is-active"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="statisticsFiltersPanel" class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="panel-title">Fuentes de datos</h4>
                </div>
                <div class="panel-body">
                    <div id="filtersSelector">
                    </div>
                    <div id="filters">
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
