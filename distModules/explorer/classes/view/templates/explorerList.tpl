<!--EXPLORADORES SOLO LISTA( NORMAL Y STICKY )-->

<div class="explorerLayout explorerLayoutSticky explorerLayoutList fixVhChromeMobile clearfix">
  <!--duContainer -->
  <div class="explorerContainer explorer-container-du"></div>
  <!--filterContainer -->
  <div class="explorerContainer explorer-container-filter explorerElSticky"></div>
  <!--galleryContainer -->
  <div class="explorerContainer explorer-container-gallery explorerElSticky clearfix"></div>

  <!--galleryContainer -->
  <div class="explorerContainer explorer-loading" style="display:none;"><i class="far fa-compass fa-spin"></i></div>
  <div class="explorerButton gzz-exit-sticky" data-toggle="tooltip" data-placement="left" title="{t}Return to content{/t}" tabindex="0"><i class="fas fa-chevron-up fa-lg" aria-hidden="true"></i></div>

  <!-- mobile explorer buttons -->
  <div class="explorer-manage-mobile explorerElSticky">
    <div class="explorer-manage-filters-mobile" style="display:none;">
      <div class="explorerButton return">{t}Apply{/t}</div>
      <div class=" explorerButton clear">{t}Clear filters{/t}</div>
    </div>
    <div class="explorerButton explorer-mobile-control-blist" style="display:none;"><i class="fas fa-list-ul"></i>&nbsp;{t}List{/t}</div>
    <div class="explorerButton explorer-mobile-control-bfilters" style="display:none;">{t}Filters{/t}&nbsp;<i class="fas fa-sliders-h" aria-hidden="true"></i></div>
  </div>
</div>
