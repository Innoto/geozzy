
{$res.dataForm.formOpen}
  {$res.dataForm.formFieldsArray.cgIntFrmId}

  <div class="row location">
    <div class="col-md-6">{$res.dataForm.formFieldsArray['rExtContact_city']}</div>
    <div class="col-md-6">{$res.dataForm.formFieldsArray['rExtContact_province']}</div>
    <div class="col-md-12 mapContainer">
      <div class="descMap">{t}Haz click en el lugar donde te ubicas, podrás arrastrar y soltar la localización{/t}</div>
    </div>
    <div class="col-md-12 locationData">
      <div class="row">
        <div class="col-md-2">{$res.dataForm.formFieldsArray['locLat']}</div>
        <div class="col-md-2">{$res.dataForm.formFieldsArray['locLon']}</div>
        <div class="col-md-2">{$res.dataForm.formFieldsArray['defaultZoom']}</div>
      </div>
    </div>
  </div>
  {$res.dataForm.formFieldsArray.submit}
{$res.dataForm.formClose}
{$res.dataForm.formValidations}
<!-- /rExtFormBlock.tpl -->
