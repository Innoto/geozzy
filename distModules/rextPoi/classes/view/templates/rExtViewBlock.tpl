<div class="poiBlock {$rExt.dataForm.formId}">

  <script>
    var formId = '{$rExt.dataForm.formId}';
  </script>
  {$client_includes}

  <div class="poiType">
    {$rExt.dataForm.formFieldsArray.rextPoi_rextPoiType}
  </div>







  <div class="row location">
    <div class="col-lg-12 mapContainer">
      <div class="descMap">Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización</div>
    </div>
    <div class="col-lg-12 locationData">
      <div class="row">
        <div class="col-md-2 lat">{$res.dataForm.formFieldsArray['locLat']}</div>
        <div class="col-md-2 lon">{$res.dataForm.formFieldsArray['locLon']}</div>
        <div class="col-md-2 zoom">{$res.dataForm.formFieldsArray['defaultZoom']}</div>
        <div class="col-md-6"><div class="automaticBtn btn btn-primary pull-right">{t}Automatic Location{/t}</div></div>
      </div>
    </div>

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
