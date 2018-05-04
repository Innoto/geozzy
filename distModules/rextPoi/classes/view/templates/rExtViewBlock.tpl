<div class="poiBlock {$rExt.dataForm.formId}">


  {$client_includes}

  <div class="poiType">
    {$rExt.dataForm.formFieldsArray.rextPoi_rextPoiType}
  </div>

  <div class="poiPanorama">
    <h4>{t}Datos Panorama{/t}</h4>
    <div class="row">
      <div class="col-sm-6">{$rExt.dataForm.formFieldsArray.rextPoi_rextPoiPitch}</div>
      <div class="col-sm-6">{$rExt.dataForm.formFieldsArray.rextPoi_rextPoiYaw}</div>
    </div>
  </div>



  <div class="location">
    <div class="mapContainer">
      <div class="descMap">{t}Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización{/t}</div>
    </div>
    <div class="locationData">
      <div class="row">
        <div class="col-md-2 lat">{$res['locLat']}</div>
        <div class="col-md-2 lon">{$res['locLon']}</div>
        <div class="col-md-2 zoom">{$res['defaultZoom']}</div>
      </div>
    </div>
  </div>

</div>
