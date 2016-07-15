<div id="participation-step2" class="participation-step2 modal fade" tabindex="-1" role="dialog" aria-labelledby="participationStep2">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {if !isset($headTitle)}{assign var='headTitle' value=''}{/if}
        <h3 class="modal-title">{$headTitle}</h3>
      </div>
      <div class="modal-body">


        {$res.dataForm.formOpen}
        {$res.dataForm.formFieldsArray.cgIntFrmId}
        {if isset($res.dataForm.formFieldsArray.id)}{$res.dataForm.formFieldsArray.id}{/if}

        <div class="subStep subStep1">
          {if isset($formFieldsNamesStp1)}
            {foreach $formFieldsNamesStp1 as $name}
              {$res.dataForm.formFieldsArray[$name]}
            {/foreach}
          {/if}
          <div class="subStepActions">
            <div class="row">
              <div class="col-sm-6">
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6">
                <button type="button" class="btn btn-success next" data-goStep="2">{t}Siguiente{/t}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="subStep subStep2" style="display:none;">
          {if isset($formFieldsNamesStp2)}
            {foreach $formFieldsNamesStp2 as $name}
              {$res.dataForm.formFieldsArray[$name]}
            {/foreach}
          {/if}
          <div class="subStepActions">
            <div class="row">
              <div class="col-sm-6">
                <button type="button" class="btn btn-success previous" data-goStep="1">{t}Anterior{/t}</button>
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6">
                <button type="button" class="btn btn-success next" data-goStep="3">{t}Siguiente{/t}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="subStep subStep3" style="display:none;">
          {if isset($formFieldsNamesStp3)}
            {foreach $formFieldsNamesStp3 as $name}
              {$res.dataForm.formFieldsArray[$name]}
            {/foreach}
          {/if}
          <div class="subStepActions">
            <div class="row">
              <div class="col-sm-6">
                <button type="button" class="btn btn-success previous" data-goStep="2">{t}Anterior{/t}</button>
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6">
                <button type="button" class="btn btn-success next" data-goStep="4">{t}Siguiente{/t}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="subStep subStep4" style="display:none;">
          <h4>{t}¿Tienes alguna foto de calidad del lugar?{/t}</h4>
          <p>{t}Selecciona o arrástrala hasta el siguiente icono{/t}</p>
          {if isset($formFieldsNamesStp4)}
            {foreach $formFieldsNamesStp4 as $name}
              {$res.dataForm.formFieldsArray[$name]}
            {/foreach}
          {/if}
          <p>{t}Si no tienes ninguna foto, pulsa en siguiente{/t}</p>
          <div class="subStepActions">
            <div class="row">
              <div class="col-sm-6">
                <button type="button" class="btn btn-success previous" data-goStep="3">{t}Anterior{/t}</button>
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6">
                <button type="button" class="btn btn-success next" data-goStep="5">{t}Siguiente{/t}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="subStep subStep5" style="display:none;">
          <h4>{t}¿Todo listo?{/t}</h4>
          <p>{t}Cuéntanos algo más que quieras y envíanos tu sugerencia{/t}</p>
          {if isset($formFieldsNamesStp5)}
            {foreach $formFieldsNamesStp5 as $name}
              {$res.dataForm.formFieldsArray[$name]}
            {/foreach}
          {/if}

          <div class="subStepActions">
            <div class="row">
              <div class="col-sm-6">
                <button type="button" class="btn btn-success previous" data-goStep="4">{t}Anterior{/t}</button>
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6">
                {$res.dataForm.formFieldsArray['submit']}
              </div>
            </div>
          </div>
        </div>


        {$res.dataForm.formClose}
        {$res.dataForm.formValidations}
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('.participation-step2').modal({
    'show' : true,
    'keyboard': false,
    'backdrop' : 'static'
  });

  $('.participation-step2').on('hidden.bs.modal', function (e) {
    $(this).remove();
  });

  $('.participation-step2 .subStepActions .next').on('click', function (e) {
    var substep = $(this).attr('data-goStep');
    $('.participation-step2 .subStep').hide();
    $('.participation-step2 .subStep'+substep).show();
  });
  $('.participation-step2 .subStepActions .previous').on('click', function (e) {
    var substep = $(this).attr('data-goStep');
    $('.participation-step2 .subStep').hide();
    $('.participation-step2 .subStep'+substep).show();
  });

</script>
