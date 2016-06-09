{extends file="primary.tpl"}


{block name="headClientIncludes" append}
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&language={$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n}"></script>
  <script rel="false" type="text/javascript" src="{$cogumelo.publicConf.media}/js/resource.js"></script>
{/block}


{block name="headTitle" append}
  {if $res.data.headTitle!='' }
    {$res.data.headTitle}
  {else}
    {$res.data.title}
  {/if}
{/block}


{block name="headDescription"}
  {if $res.data.headDescription!='' }{$res.data.headDescription}{else}{$res.data.shortDescription}{/if}
{/block}


{block name="socialMeta" append}
  {$l = $cogumelo.publicConf.C_LANG}
  <meta property="og:url" content="{$cogumelo.publicConf.site_host}{$res.data["urlAlias"]}" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content='{$res.data["title_$l"]|escape:"html"}'/>
  <meta property="og:image" content="{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}/big/{$res.data.image.id}.jpg" />
  <meta property="og:description" content='{$res.ext.rextSocialNetwork.data["textFb"]|escape:"html"}' />
  <meta property="og:locale" content="{$cogumelo.publicConf.lang_available[$l]['i18n']}"/>
{/block}


{block name="bodyContent"}
<!-- appResourceBridgePageFull.tpl en appResourceBridge module -->
  {$resTemplateBlock}
<!-- /appResourceBridgePageFull.tpl en appResourceBridge module -->
{/block}
