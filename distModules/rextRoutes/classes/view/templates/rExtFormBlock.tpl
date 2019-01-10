<div class="rExtRoutes formBlock">
  <div class="row">
    <div class="col-12">
      <div class="circularSelector">
        {$rExt.dataForm.formFieldsArray.rExtRoutes_circular}
      </div>
    </div>

    <div class="col-12 col-lg-6">
      {$rExt.dataForm.formFieldsArray.rExtRoutes_durationMinutes}
      {$rExt.dataForm.formFieldsArray.rExtRoutes_travelDistance}
      {$rExt.dataForm.formFieldsArray.rExtRoutes_slopeUp}
      {$rExt.dataForm.formFieldsArray.rExtRoutes_slopeDown}
    </div>

    <div class="col-12 col-lg-6">
      {if isset($rExt.dataForm.formFieldsArray.rExtRoutes_difficultyEnvironment)}
        {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyEnvironment}
      {/if}
      {if isset($rExt.dataForm.formFieldsArray.rExtRoutes_difficultyItinerary)}
        {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyItinerary}
      {/if}
      {if isset($rExt.dataForm.formFieldsArray.rExtRoutes_difficultyDisplacement)}
        {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyDisplacement}
      {/if}
      {if isset($rExt.dataForm.formFieldsArray.rExtRoutes_difficultyEffort)}
        {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyEffort}
      {/if}
      {if isset($rExt.dataForm.formFieldsArray.rExtRoutes_difficultyGlobal)}
        {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyGlobal}
      {/if}
    </div>

    <div class="col-12">
      {foreach $cogumelo.publicConf.langAvailableIds as $lang}
        {$rExt.dataForm.formFieldsArray["rExtRoutes_routeStart_$lang"]}
      {/foreach}

      {foreach $cogumelo.publicConf.langAvailableIds as $lang}
        {$rExt.dataForm.formFieldsArray["rExtRoutes_routeEnd_$lang"]}
      {/foreach}

      {$rExt.dataForm.formFieldsArray.rExtRoutes_routeFile}
    </div>

    <div style="display:none;">
      {$rExt.dataForm.formFieldsArray.rExtRoutes_locStartLat}
      {$rExt.dataForm.formFieldsArray.rExtRoutes_locStartLon}

      {$rExt.dataForm.formFieldsArray.rExtRoutes_locEndLat}
      {$rExt.dataForm.formFieldsArray.rExtRoutes_locEndLon}
    </div>
  </div>
</div>
  {$client_includes}
