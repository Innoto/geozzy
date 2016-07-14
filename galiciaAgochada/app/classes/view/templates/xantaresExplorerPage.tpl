
  <div class="titleBar">
    <div class="container">
      <img class="iconTitleBar img-responsive" alt="{t}Sabrosos xantares{/t}" src="{$cogumelo.publicConf.media}/img/xantaresIcon.png">
      <h1>{t}Sabrosos xantares{/t}</h1>
    </div>
  </div>
  <div class="xantaresExplorer explorerCommonSagan">
    <div class="explorerLayout clearfix">
      <!--duContainer -->
      <div class="explorerContainer explorer-container-du"></div>
      <!--filterContainer -->
      <div class="explorerContainer explorer-container-filter"></div>
      <!--mapContainer -->
      <div class="explorerContainer explorer-container-map">
        <div class="explorerMap"></div>
        <!-- Participation -->
        <button id="initParticipacion"><i class="fa fa-map-pin" aria-hidden="true"></i></button>
        <div class="participation-step1" style="display:none;">
          <div class="contentModalParticipation">
            <h3>{t}Estas engadindo un novo contido?{/t}</h3>
            <p>{t}Podes engadir unha nova ubicación ou axustar a seleccionada do lugar arrastrando é soltado. Pulsa continuar cando remates.{/t}</p>
            <p>{t}Fai todo o zoom que podas para ser preciso{/t}</p>
          </div>
          <div class="actionsModalParticipation">
            <button class="btn btn-warning cancel" type="button">{t}Cancelar{/t}</button>
            <button disabled class="btn btn-success next" type="button">{t}Continuar{/t}</button>
          </div>
        </div>
      </div>
      <!--galleryContainer -->
      <div class="explorerContainer explorer-container-gallery"></div>
      <!--galleryContainer -->
      <div class="explorerContainer explorer-loading" style="display:none;"><i class="fa  fa-compass fa-spin"></i></div>
    </div>
  </div>
