<div class="eventBlock">
  {$client_includes}

  <style type="text/css">
    .eventBlock .form-group .input-group.date .datetimepicker-input.form-control{ height: auto; }
  </style>

  {foreach from=$cogumelo.publicConf.langAvailableIds item=lang}
    {$rExt.dataForm.formFieldsArray['rextEvent_eventTitle_'|cat:$lang]}
  {/foreach}

  <div class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-12">
          <div class="row">
            <div class="col-sm-6">
              {$rExt.dataForm.formFieldsArray.rextEvent_selectInitTime}
            </div>
            <div class="col-sm-6">
              <label class="cgmMForm">{t}Event init{/t}</label>
              <div class="form-group">
                <div class="input-group date initDateFirst" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target=".initDateFirst">
                  <div class="input-group-append" data-target=".initDateFirst" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <input class="cgmMForm-field cgmMForm-field-rextEvent_initDateFirst" type="hidden" form="{$rExt.dataForm.formId}" value="{$rExt.data.initDateFirst}" name="rextEvent_initDateFirst">
              <input class="cgmMForm-field cgmMForm-field-rextEvent_initDateSecond" type="hidden" form="{$rExt.dataForm.formId}" value="{$rExt.data.initDateSecond}" name="rextEvent_initDateSecond">
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-sm-6">
              <label class="cgmMForm js-hidden-initTimeFirst">{t}Time init{/t} ({t}Event init{/t})</label>
              <div class="form-group js-hidden-initTimeFirst">
                <div class="input-group date initTimeFirst" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target=".initTimeFirst">
                  <div class="input-group-append" data-target=".initTimeFirst" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fas fa-clock"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <label class="cgmMForm js-hidden-initTimeSecond">{t}Time end{/t} ({t}Event init{/t})</label>
              <div class="form-group js-hidden-initTimeSecond">
                <div class="input-group date initTimeSecond" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target=".initTimeSecond">
                  <div class="input-group-append" data-target=".initTimeSecond" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fas fa-clock"></i></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      {$rExt.dataForm.formFieldsArray.rextEvent_dateRange}
    </div>

    <div class="col-12 js-hidden-boxEndDate">
      <div class="row">
        <div class="col-12">
          <div class="row">
            <div class="col-sm-6">
              {$rExt.dataForm.formFieldsArray.rextEvent_selectEndTime}
            </div>
            <div class="col-sm-6">
              <label class="cgmMForm">{t}Event end{/t}</label>
              <div class="form-group">
                <div class="input-group date endDateFirst" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target=".endDateFirst">
                  <div class="input-group-append" data-target=".endDateFirst" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
              <input class="cgmMForm-field cgmMForm-field-rextEvent_endDateFirst" type="hidden" form="{$rExt.dataForm.formId}" value="{$rExt.data.endDateFirst}" name="rextEvent_endDateFirst">
              <input class="cgmMForm-field cgmMForm-field-rextEvent_endDateSecond" type="hidden" form="{$rExt.dataForm.formId}" value="{$rExt.data.endDateSecond}" name="rextEvent_endDateSecond">
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-sm-6">
              <label class="cgmMForm js-hidden-endTimeFirst">{t}Time init{/t} ({t}Event end{/t})</label>
              <div class="form-group js-hidden-endTimeFirst">
                <div class="input-group date endTimeFirst" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target=".endTimeFirst">
                  <div class="input-group-append" data-target=".endTimeFirst" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fas fa-clock"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <label class="cgmMForm js-hidden-endTimeSecond">{t}Time end{/t} ({t}Event end{/t})</label>
              <div class="form-group js-hidden-endTimeSecond">
                <div class="input-group date endTimeSecond" data-target-input="nearest">
                  <input type="text" class="form-control datetimepicker-input" data-target=".endTimeSecond">
                  <div class="input-group-append" data-target=".endTimeSecond" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fas fa-clock"></i></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {$rExt.dataForm.formFieldsArray.rextEvent_rextEventType|default:''}

  {$rExt.dataForm.formFieldsArray.rextEvent_relatedResource|default:''}
</div>
