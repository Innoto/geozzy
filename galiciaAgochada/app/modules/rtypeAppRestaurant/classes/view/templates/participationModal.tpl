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

        {if isset($formFieldsNamesStp1)}
          {foreach $formFieldsNamesStp1 as $name}
            {$res.dataForm.formFieldsArray[$name]}
          {/foreach}
        {/if}

        {if isset($formFieldsNamesStp2)}
          {foreach $formFieldsNamesStp2 as $name}
            {$res.dataForm.formFieldsArray[$name]}
          {/foreach}
        {/if}

        {if isset($formFieldsNamesStp3)}
          {foreach $formFieldsNamesStp3 as $name}
            {$res.dataForm.formFieldsArray[$name]}
          {/foreach}
        {/if}

        {if isset($formFieldsNamesStp4)}
          {foreach $formFieldsNamesStp4 as $name}
            {$res.dataForm.formFieldsArray[$name]}
          {/foreach}
        {/if}

        {if isset($formFieldsNamesStp5)}
          {foreach $formFieldsNamesStp5 as $name}
            {$res.dataForm.formFieldsArray[$name]}
          {/foreach}
        {/if}

          {$res.dataForm.formFieldsArray['submit']}






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

</script>
