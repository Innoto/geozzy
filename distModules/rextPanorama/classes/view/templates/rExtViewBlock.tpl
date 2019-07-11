<!-- rExtViewBlock.tpl en rExtPanorama module -->
<div class="rExtPanorama">

  {if empty($rExt.data.panoramicImage)}
    <script type="text/javascript">
      var geozzy = geozzy || {};
      geozzy.rExtPanoramaOptions = {
        horizontalAnglePanorama: false,
        verticalAnglePanorama: false,
        // showPanorama: false,
        panoramaImg: false,
        resourceID: {$rExt.data.id}
      };
    </script>
  {else}
    <script type="text/javascript">
      var geozzy = geozzy || {};
      if( !cogumelo.publicConf.mod_detectMobile_isMobile ) {
        geozzy.rExtPanoramaOptions = {
          horizontalAnglePanorama: {$rExt.data.horizontalAngleView},
          verticalAnglePanorama: {$rExt.data.verticalAngleView},
          // showPanorama: true,
          panoramaImg: cogumelo.publicConf.site_host+"/cgmlImg/{$rExt.data.panoramicImage.id}-a{$rExt.data.panoramicImage.aKey}/panorama/panorama.jpg",
          resourceID: {$rExt.data.id}
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
  {/if}

  <div id="panoramaView" style="display:none;height:350px;background-color:grey;"></div>

</div>
<!-- /rExtViewBlock.tpl en rExtPanorama module -->
