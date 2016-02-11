{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&language={$GLOBAL_LANG_AVAILABLE[$GLOBAL_C_LANG].i18n}"></script>
{/block}

{block name="bodyContent"}
  <div class="praiasExplorer explorerCommonBase">
    {include file="explorer///explorer.tpl"}
  </div>
  <div class="modalWelcome modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <img src="/media/img/explorer/modal_img_praia.jpg" class="img-responsive">
        <div class="paddingContent">
          <h2>{t}Praias de ensono{/t}</h2>
          <p>posuere sem nulla, vel ultrices ipsum vehicula non. Phasellus mollis, sapien hendrerit venenatis egestas, nibh ante eleifend nibh, quis faucibus sapien turpis at erat. Cras justo quam, mollis auctor mi in, fringilla varius purus. Etiam venenatis ex odio, sit amet ornare sapien aliquet sit amet. In magna erat, efficitur volutpat est a, tristique consectetur turpis. Proin a tortor hendrerit ligula vestibulum lacinia.</p>
          <p>Etiam at purus id turpis euismod euismod et non purus. Vivamus tempor mollis iaculis. </p>
          <button class="btn" data-dismiss="modal">{t}Explóraos{/t}</button>
        </div>
      </div>

    </div>
  </div>
{/block}

{block name="footerContent"}

{/block}
