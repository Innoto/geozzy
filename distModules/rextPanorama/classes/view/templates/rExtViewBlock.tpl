<!-- rExtViewBlock.tpl en rExtPanorama module -->
<div class="rExtPanorama">
  <script type="text/javascript">
    if( !cogumelo.publicConf.mod_detectMobile_isMobile ) {
      var geozzy = geozzy || {};
      geozzy.rExtPanoramaOptions = {
        showPanorama: {if !empty($rExt.data.panoramicImage)}true{else}false{/if},
        horizontalAnglePanorama: {if !empty($rExt.data.panoramicImage)}{$rExt.data.horizontalAngleView}{else}false{/if},
        verticalAnglePanorama: {if !empty($rExt.data.panoramicImage)}{$rExt.data.verticalAngleView}{else}false{/if},
        panoramaImg: {if !empty($rExt.data.panoramicImage)}cogumelo.publicConf.site_host+"/cgmlImg/{$rExt.data.panoramicImage.id}-a{$rExt.data.panoramicImage.aKey}/panoramica/panorama.jpg"{else}false{/if}
      };
    }
  </script>

  <style>
    {literal}
      .panorama-custom-hotspot{
        height: 15px;
        width: 15px;
        border-radius: 50%;
        background: #D35E5E;
        border: 2px solid white;
      }

      .panorama-custom-hotspot-selected{
        border-width: 3px;
        margin-left: -1px;
        margin-top: -1px;
      }
    {/literal}
  </style>

  <div id="imgPanoramaView" style="display:none;height:350px;background-color:grey;"></div>

</div>
<!-- /rExtViewBlock.tpl en rExtPanorama module -->
