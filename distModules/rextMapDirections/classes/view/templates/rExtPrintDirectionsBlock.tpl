{extends file="rextMapDirections///rExtPrintDirectionsPageFull.tpl"}


{block name="headClientIncludes" prepend}
{$gMaps = "https://maps.googleapis.com/maps/api/js?language=`$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n`"}
{if isset($cogumelo.publicConf.google_maps_key) && $cogumelo.publicConf.google_maps_key}
{$gMaps = "`$gMaps`&key=`$cogumelo.publicConf.google_maps_key`"}
{/if}
  <script type="text/javascript" src="{$gMaps}&libraries=places"></script>
{/block}


{block name="bodyContent"}
<!-- rExtPrintDirectionsBlock.tpl en rextMapDirections module -->
  <style>
    #map{ height: 50vh; }
    .content{
      width: 50%;
      margin: auto;
    }
    .content .title{ text-align: center; }
    @media print {
      .content{ width: 100%; }
    }
  </style>
  <script type="text/javascript">
    var geozzy = geozzy || {};
    geozzy.app = geozzy.app || {};

    geozzy.rextMapDirections = {
      directions: {if !empty($mapDirections)}{$mapDirections|@json_encode nofilter}{else}false{/if}
    };
  </script>

  <div class="content">
    <h1 class="title">{$mapDirections.title}</h1>
    <div id="map"></div>
    <div id="routeDirectionList"></div>
  </div>
<!-- /rExtPrintDirectionsBlock.tpl en rextMapDirections module -->
{/block}
