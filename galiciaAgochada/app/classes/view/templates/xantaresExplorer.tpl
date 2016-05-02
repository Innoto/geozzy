{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&language={$cogumelo.publicConf.lang_available[$cogumelo.publicConf.lang_available.C_LANG].i18n}"></script>
{/block}

{block name="bodyContent"}
  <div class="titleBar">
    <div class="container">
      <img class="iconTitleBar img-responsive" alt="{t}Sabrosos xantares{/t}" src="{$cogumelo.publicConf.media}/img/xantaresIcon.png"></img>
      <h1>{t}Sabrosos xantares{/t}</h1>
    </div>
  </div>
  <div class="xantaresExplorer explorerCommonSagan">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}


{block name="footerContent"}

{/block}
