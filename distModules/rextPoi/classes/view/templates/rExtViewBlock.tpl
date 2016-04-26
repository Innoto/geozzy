{extends file="admin///adminPanel.tpl"}


{block name="content"}


<div class="poiBlock">
  {$client_includes}

  <div class="col-lg-12 poiType">
    {$rExt.rextPoi.dataForm.formFieldsArray.rextPoi_rextPoiType}
  </div>
  <div class="col-lg-12 mapContainer">
    <div class="descMap">Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización</div>
  </div>
  <div class="col-lg-12 locationData">
    <div class="row">
      <div class="col-md-2">{$res.dataForm.formFieldsArray['locLat']}</div>
      <div class="col-md-2">{$res.dataForm.formFieldsArray['locLon']}</div>
      <div class="col-md-2">{$res.dataForm.formFieldsArray['defaultZoom']}</div>
    </div>
  </div>

</div>
{/block}{*/content*}
