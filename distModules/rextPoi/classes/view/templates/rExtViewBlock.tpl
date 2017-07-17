<div class="poiBlock {$rExt.dataForm.formId}">


  {$client_includes}

  <div class="poiType">
    {$rExt.dataForm.formFieldsArray.rextPoi_rextPoiType}
  </div>









  <div class="location">
    <div class="mapContainer">
      <div class="descMap">Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización</div>
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
