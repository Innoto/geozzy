{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&language={$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n}"></script>
{/block}

{block name="bodyContent"}
  <div class="rinconsExplorer explorerCommonBase">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}

{block name="footerContent"}

{/block}
