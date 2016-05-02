{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&language={$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n}"></script>
{/block}

{block name="bodyContent"}
  <div class="titleBar">
    <div class="container">
      <img class="iconTitleBar img-responsive" alt="{t}Aloxamentos con encanto{/t}" src="{$cogumelo.publicConf.media}/img/aloxamentosIcon.png"></img>
      <h1>{t}Aloxamentos con encanto{/t}</h1>
    </div>
  </div>
  <div class="aloxamentosExplorer explorerCommonSagan">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}


{block name="footerContent"}

{/block}
