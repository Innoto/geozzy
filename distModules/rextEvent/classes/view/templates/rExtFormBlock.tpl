<div class="eventBlock">
  {$client_includes}

  {foreach from=$cogumelo.publicConf.langAvailableIds item=lang}
    {$rExt.dataForm.formFieldsArray['rextEvent_literalDate_'|cat:$lang]}
  {/foreach}

  <div class="row">
    <div class="col-md-6">
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
    </div>

    <div class="col-md-6">
      <label class="cgmMForm">{t}Event end date{/t}</label>
      <div class="form-group">
        <div class="input-group date endDate">
          <input type="text" class="form-control" />
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
          </span></div>
      </div>
      <input class="cgmMForm-field cgmMForm-field-rextEvent_endDate" type="hidden" form="{$rExt.dataForm.formId}" value="{$rExt.data.endDate}" name="rextEvent_endDate">
    </div>
  </div>

  {$rExt.dataForm.formFieldsArray.rextEvent_rextEventType|default:''}

  {$rExt.dataForm.formFieldsArray.rextEvent_relatedResource|default:''}
</div>
