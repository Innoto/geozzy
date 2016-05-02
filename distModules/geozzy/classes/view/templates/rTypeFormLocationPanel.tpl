{extends file="admin///adminPanel.tpl"}


{block name="content"}

<script>
  var poiFormId = '{$res.dataForm.formId}';
</script>

<div class="row location {$res.dataForm.formId}">
    <div class="col-lg-12 mapContainer">
      <div class="descMap">Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización</div>
    </div>
    <div class="col-lg-12 locationData">
      <div class="row">
        <div class="col-md-2">{$res.dataForm.formFieldsArray['locLat']}</div>
        <div class="col-md-2">{$res.dataForm.formFieldsArray['locLon']}</div>
        <div class="col-md-2">{$res.dataForm.formFieldsArray['defaultZoom']}</div>
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
