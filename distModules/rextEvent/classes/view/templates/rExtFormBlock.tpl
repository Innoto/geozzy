<div class="eventBlock">
  {$client_includes}

  <style type="text/css">
    .eventBlock .form-group .input-group.date .datetimepicker-input.form-control{ height: auto; }
  </style>

  {foreach from=$cogumelo.publicConf.langAvailableIds item=lang}
    {$rExt.dataForm.formFieldsArray['rextEvent_eventTitle_'|cat:$lang]}
  {/foreach}

  <div class="row">
    <div class="col-sm-6">
      <label class="cgmMForm">{t}Event init date{/t}</label>
      <div class="form-group">
        <div class="input-group date initDate" data-target-input="nearest">
          <input type="text" class="form-control datetimepicker-input" data-target=".initDate"/>
          <div class="input-group-append" data-target=".initDate" data-toggle="datetimepicker">
              <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
          </div>
        </div>
      </div>
      <input class="cgmMForm-field cgmMForm-field-rextEvent_initDate" type="hidden" form="{$rExt.dataForm.formId}" value="{$rExt.data.initDate}" name="rextEvent_initDate">
    </div>

    <div class="col-sm-6">
      <label class="cgmMForm">{t}Event end date{/t}</label>
      <div class="form-group">
        <div class="input-group date endDate" data-target-input="nearest">
          <input type="text" class="form-control datetimepicker-input" data-target=".endDate"/>
          <div class="input-group-append" data-target=".endDate" data-toggle="datetimepicker">
              <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
          </div>
        </div>
      </div>
      <input class="cgmMForm-field cgmMForm-field-rextEvent_endDate" type="hidden" form="{$rExt.dataForm.formId}" value="{$rExt.data.endDate}" name="rextEvent_endDate">
    </div>
  </div>

  {$rExt.dataForm.formFieldsArray.rextEvent_rextEventType|default:''}

  {$rExt.dataForm.formFieldsArray.rextEvent_relatedResource|default:''}
</div>
