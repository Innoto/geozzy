{extends file="admin///adminPanel.tpl"}


{block name="content"}



<div class="row location">
  <div class="buttonEditLocation btn btn-primary pull-right">
    <span class="startEdit">Edit location</span>
    <span class="endEdit">End location edit</span>
  </div>


  <div class="locationForm">
    <div class="col-md-12">
      <div class="row row-eq-height-vertical-centered">

        <div class="col-md-12 col-sm-12 col-12 ">
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
        <div class="col-md-6 lat">{$res.dataForm.formFieldsArray['locLat']}</div>
        <div class="col-md-6 lon">{$res.dataForm.formFieldsArray['locLon']}</div>
      </div>
      <div class="row">

        <div class="col-md-12 zoom">{$res.dataForm.formFieldsArray['defaultZoom']}</div>

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


  {if isset($extraLocationForms)}
    {$extraLocationForms}
  {/if}
</div>



{/block}{*/content*}
