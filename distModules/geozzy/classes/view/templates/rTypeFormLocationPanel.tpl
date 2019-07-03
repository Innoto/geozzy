{extends file="admin///adminPanel.tpl"}


{block name="content"}



<div class="row location">

  <div class="locationForm" style="display:none;">
    <div class="col-md-12">
      <div class="row row-eq-height-vertical-centered">
        <div class="col-md-6 col-sm-6 col-12"><input class="address"></div>
        <div class="col-md-6 col-sm-6 col-12 ">
          <div class="descMap">
            {t}Haz click en el lugar donde se ubica el recurso con la mayor precisión que puedas, podrás arrastrar y soltar la localización{/t}
          </div>
        </div>
      </div>
    </div>


    <div class="col-lg-12 mapContainer">

    </div>
    <div class="col-lg-12 locationData">
      <div class="row">
        <div class="col-md-2 lat">{$res.dataForm.formFieldsArray['locLat']}</div>
        <div class="col-md-2 lon">{$res.dataForm.formFieldsArray['locLon']}</div>
        <div class="col-md-2 zoom">{$res.dataForm.formFieldsArray['defaultZoom']}</div>
        <div class="col-md-6"><div class="automaticBtn btn btn-primary pull-right">{t}Automatic Location{/t}</div></div>
      </div>
    </div>
    {if isset($directions)}
      <div class="col-lg-12 locationDirections">
        {foreach $directions as $dir}
          {if isset($res.dataForm.formFieldsArray[$dir])}
            {$res.dataForm.formFieldsArray[$dir]}
          {/if}
        {/foreach}
      </div>
    {/if}
  </div>
</div>



{/block}{*/content*}
