
<div class="rExtRoutes formBlock">

  <div class="left-column col-lg-6">
    <div class="circularSelector">
      {$rExt.dataForm.formFieldsArray.rExtRoutes_circular}
    </div>
    {$rExt.dataForm.formFieldsArray.rExtRoutes_durationMinutes}
    {$rExt.dataForm.formFieldsArray.rExtRoutes_travelDistance}
    {$rExt.dataForm.formFieldsArray.rExtRoutes_slopeUp}
    {$rExt.dataForm.formFieldsArray.rExtRoutes_slopeDown}
  </div>

  <div class="right-column col-lg-6">
    {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyEnvironment}
    {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyItinerary}
    {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyDisplacement}
    {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyEffort}
    {$rExt.dataForm.formFieldsArray.rExtRoutes_difficultyGlobal}
  </div>

  <div class="col-lg-12">
    {foreach $cogumelo.publicConf.langAvailableIds as $lang}
      {$rExt.dataForm.formFieldsArray["rExtRoutes_routeStart_$lang"]}
    {/foreach}

    {foreach $cogumelo.publicConf.langAvailableIds as $lang}
      {$rExt.dataForm.formFieldsArray["rExtRoutes_routeEnd_$lang"]}
    {/foreach}

    {$rExt.dataForm.formFieldsArray.rExtRoutes_routeFile}
  </div>

</div>

{$client_includes}
