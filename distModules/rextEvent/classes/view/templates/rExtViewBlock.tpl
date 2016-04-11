<div class="eventBlock">
  {$client_includes}

  <label class="cgmMForm">{t}Event init date{/t}</label>
  <div class="form-group">
    <div class="input-group date initDate">
      <input type="text" class="form-control" />
      <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
      </span>
    </div>
  </div>
  <input class="cgmMForm-field cgmMForm-field-rextEvent_initDate" type="hidden" form="{$rExt.dataForm.formId}" value="{$rExt.data.initDate}" name="rextEvent_initDate">


  <label class="cgmMForm">{t}Event end date{/t}</label>
  <div class="form-group">
    <div class="input-group date endDate">
      <input type="text" class="form-control" />
      <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
      </span></div>
  </div>
  <input class="cgmMForm-field cgmMForm-field-rextEvent_endDate" type="hidden" form="{$rExt.dataForm.formId}" value="{$rExt.data.endDate}" name="rextEvent_endDate">

  {$rExt.dataForm.formFieldsArray.rextEvent_rextEventType}

  {$rExt.dataForm.formFieldsArray.rextEvent_relatedResource}
</div>
