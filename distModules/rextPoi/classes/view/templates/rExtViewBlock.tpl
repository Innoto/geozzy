<div class="poiBlock {$rExt.dataForm.formId}">

OLA??????


  <script>
    var formId = '{$rExt.dataForm.formId}';
  </script>
  {$client_includes}

  <div class="poiType">
    {$rExt.dataForm.formFieldsArray.rextPoi_rextPoiType}
  </div>
  <div class="mapContainer">
    <div class="descMap">Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización</div>
  </div>
  <div class="locationData">
    <div class="row">
      <div class="col-md-2">{$res['locLat']}</div>
      <div class="col-md-2">{$res['locLon']}</div>
      <div class="col-md-2">{$res['defaultZoom']}</div>
    </div>
  </div>

</div>
