<div id="participation-step2" class="participation-step2 modal fade" tabindex="-1" role="dialog" aria-labelledby="participationStep2">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <img class="iconModal img-responsive" src="{$cogumelo.publicConf.media}/img/iconModal.png"></img>
        {if !isset($headTitle)}{assign var='headTitle' value=''}{/if}
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
              <div class="col-sm-6 col-xs-12">
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6 col-xs-12">
                <button type="button" class="btn btn-success next" data-goStep="2">{t}Seguinte{/t}</button>
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
              <div class="col-sm-6 col-xs-12">
                <button type="button" class="btn btn-success previous" data-goStep="1">{t}Anterior{/t}</button>
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6 col-xs-12">
                <button type="button" class="btn btn-success next" data-goStep="3">{t}Seguinte{/t}</button>
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
              <div class="col-sm-6 col-xs-12">
                <button type="button" class="btn btn-success previous" data-goStep="2">{t}Anterior{/t}</button>
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6 col-xs-12">
                <button type="button" class="btn btn-success next" data-goStep="4">{t}Seguinte{/t}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="subStep subStep4" style="display:none;">
          <h4>{t}Tes algunha foto de calidade do lugar?{/t}</h4>
          <p>{t}Selecciona ou arrástraa ata a seguinte icona{/t}</p>
          {if isset($formFieldsNamesStp4)}
            {foreach $formFieldsNamesStp4 as $name}
              {$res.dataForm.formFieldsArray[$name]}
            {/foreach}
          {/if}
          <p>{t}Se non tes ningunha foto, pulsa en seguinte{/t}</p>
          <div class="subStepActions">
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <button type="button" class="btn btn-success previous" data-goStep="3">{t}Anterior{/t}</button>
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6 col-xs-12">
                <button type="button" class="btn btn-success next" data-goStep="5">{t}Seguinte{/t}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="subStep subStep5" style="display:none;">
          <h4>{t}Todo listo?{/t}</h4>
          <p>{t}Cóntanos algo máis que queiras e envíanos a túa suxestión{/t}</p>
          {if isset($formFieldsNamesStp5)}
            {foreach $formFieldsNamesStp5 as $name}
              {$res.dataForm.formFieldsArray[$name]}
            {/foreach}
          {/if}

          <div class="termsConditions" style="display:none;">
            Nulla sit amet congue felis. Aliquam eget dui nec justo mattis faucibus et in felis. Aliquam auctor, orci sit amet gravida placerat, dolor nisi tincidunt magna, eu iaculis elit ligula ac sem. Donec dapibus elit ut lorem sagittis, at porta mi tempus. Aenean porta a enim vitae fermentum. Donec sed fringilla nunc, sed fermentum odio. Sed eget massa in leo tincidunt facilisis sit amet eu est. Vestibulum aliquet diam ac neque commodo aliquet. Maecenas eu eleifend risus. Sed porttitor nulla nec posuere interdum. Duis facilisis consectetur faucibus. Pellentesque fermentum, neque ut auctor eleifend, justo purus efficitur orci, nec porttitor magna erat ac arcu.
            Proin interdum urna sit amet ante suscipit ullamcorper. Integer bibendum accumsan sagittis. Duis mollis ut justo ac aliquam. Integer scelerisque efficitur urna, eu lobortis turpis sagittis in. Vestibulum venenatis placerat augue, vel tincidunt massa dignissim ac. Donec eu libero sollicitudin, lobortis ipsum quis, porta dui. Fusce iaculis, sem ut mattis molestie, nulla sem fermentum lectus, eget scelerisque massa nibh sed sem. Nulla sed convallis quam, eu placerat elit. Ut id faucibus diam, sit amet ullamcorper nunc. Phasellus lacinia facilisis purus et vestibulum. Praesent sollicitudin mollis vulputate. Cras aliquet, mi id tincidunt dignissim, justo justo mattis ligula, blandit efficitur sem massa vitae arcu.
            Quisque hendrerit pellentesque arcu nec pharetra. Phasellus vitae neque a dolor auctor rutrum ut eget nunc. Cras euismod est tempus diam sollicitudin, nec varius arcu suscipit. Vestibulum fermentum leo tristique ornare porta. Curabitur hendrerit eleifend arcu, ut tempor velit sodales vitae. In viverra convallis turpis, euismod molestie nisl posuere sit amet. Cras at libero in mauris pulvinar volutpat. Etiam sit amet luctus urna.
          </div>

          {$res.dataForm.formCaptcha}

          <div class="formErrors"></div>
          <div class="subStepActions">
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <button type="button" class="btn btn-success previous" data-goStep="4">{t}Anterior{/t}</button>
                <button type="button" class="btn btn-warning cancel" data-dismiss="modal" aria-label="Close">{t}Cancelar{/t}</button>
              </div>
              <div class="col-sm-6 col-xs-12">
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
    $('#initParticipation').show();
  });

  $('.participation-step2 .subStepActions .next').on('click', function (e) {
    var substep = $(this).attr('data-goStep');
    var formId ='participationXantaresForm';
    var fieldsNamesArray = new Array();
    var isOk = true;


    switch (substep) {
      case '2':
        fieldsNamesArray = ['title_'+cogumelo.publicConf.C_LANG, 'mediumDescription_'+cogumelo.publicConf.C_LANG];
        $.each(fieldsNamesArray, function( index, field ) {
          if(!$('[form="'+formId+'"][name="'+field+'"]' ).valid()){
            isOk = false;
          }
        });
        break;
      case '3':
        fieldsNamesArray = ['rextEatAndDrink_eatanddrinkType'];
        $.each(fieldsNamesArray, function( index, field ) {
          if(!$('[form="'+formId+'"][name="'+field+'"]' ).valid()){
            isOk = false;
          }
        });
        break;
    }
    if(isOk){
      $('.participation-step2 .subStep').hide();
      $('.participation-step2 .subStep'+substep).show();
    }

  });
  $('.participation-step2 .subStepActions .previous').on('click', function (e) {
    var substep = $(this).attr('data-goStep');
    $('.participation-step2 .subStep').hide();
    $('.participation-step2 .subStep'+substep).show();
  });

  $('.cgmMForm-field-acceptCond .labelText span').on('click', function(e){
    $('.termsConditions').toggle();
  });

  //Modificar el selector de type por uno personalizado
  var selectXantaresType = $('select.cgmMForm-field-rextEatAndDrink_eatanddrinkType');
  var selectXantaresTypeOpt = selectXantaresType.find('option');
  selectXantaresType.hide();

  var customSelectTypeHtml = '<div class="customEatanddrinkType"><div class="row">';
  $.each(selectXantaresTypeOpt, function( index, opt ) {

    customSelectTypeHtml += '<div class="col-sm-2 col-xs-6">';
      customSelectTypeHtml += '<div class="customEatanddrinkTypeItem" data-item-val="'+$(opt).val()+'">';
        customSelectTypeHtml += '<div class="icon">';
          customSelectTypeHtml += '<img class="normal" src="/cgmlImg/'+$(opt).attr("data-term-icon")+'/typeXantaresParticipationFilter/icon.png">';
          customSelectTypeHtml += '<img class="selected" src="/cgmlImg/'+$(opt).attr("data-term-icon")+'/typeXantaresParticipationFilterActive/icon.png">';
        customSelectTypeHtml += '</div>';
        customSelectTypeHtml += '<div class="text">'+$(opt).text()+'</div>';
      customSelectTypeHtml += '</div>';
    customSelectTypeHtml += '</div>';
  });
  customSelectTypeHtml += '</div></div>';
  selectXantaresType.parent().append(customSelectTypeHtml);

  $('.customEatanddrinkType .customEatanddrinkTypeItem').on('click', function(e){
    var opt = selectXantaresType.find('option[value="'+$(this).attr("data-item-val")+'"]');
    if(opt.prop("selected")){
      opt.prop("selected", false);
      $(this).removeClass('selected');
    }else{
      opt.prop("selected", true);
      $(this).addClass('selected');
    }
  });


  var geozzy = geozzy || {};

  geozzy.xantaresParticipationForm = {
    closeModal: function closeModal() {
      $('.participation-step2').modal('hide');
    }
  };

</script>
