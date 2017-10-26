
{extends file="admin///adminPanel.tpl"}

{block name="content"}
<!-- /rTypePoiFormModalBlock.tpl para admin module -->

  <div class="poiModal">
    {$res.dataForm.formOpen}
    {$res.dataForm.formFieldsArray.cgIntFrmId}
<!--    {$res.dataForm.formFieldsArray.id}
    {$res.dataForm.formFieldsArray.rTypeId}-->

    {foreach $cogumelo.publicConf.langAvailableIds as $lang}
      {$res.dataForm.formFieldsArray["title_$lang"]}
    {/foreach}
    {foreach $cogumelo.publicConf.langAvailableIds as $lang}
      {$res.dataForm.formFieldsArray["mediumDescription_$lang"]}
    {/foreach}


    {$rextPoiBlock}

    {$res.dataForm.formFieldsArray.image}
    <label class="resImageMoreInfo">*{t}If not uploaded, an automatic thumbnail will be created{/t}</label>

    {$res.dataForm.formFieldsArray.submit}
    {$res.dataForm.formClose}
    {$res.dataForm.formValidations}
  </div>

<!-- /rTypePoiFormModalBlock.tpl para admin module -->
{/block}{*/content*}
