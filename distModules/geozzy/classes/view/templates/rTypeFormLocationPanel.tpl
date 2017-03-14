{extends file="admin///adminPanel.tpl"}


{block name="content"}



<div class="row location">

  <div class="col-lg-12">
    <div class="col-md-6" >
      <label class="col-md-5">{t}Search an address{/t}</label>
      <input class="col-md-7 address" >
    </div>
    <div class="col-md-6 descMap">Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización</div>
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



{/block}{*/content*}
