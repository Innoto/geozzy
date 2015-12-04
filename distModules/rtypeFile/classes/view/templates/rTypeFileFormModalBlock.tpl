
{extends file="admin///adminPanel.tpl"}
{block name="content"}
  <style>
    label { display: block; }
    .cgmMForm-field { max-width: none !important; }
  </style>
  <script type="text/javascript" src="{$mediaJs}/module/admin/js/adminResourceTypeMultimedia.js"></script>

  {$res.dataForm.formOpen}
    {$res.dataForm.formFieldsArray.cgIntFrmId}
    {$res.dataForm.formFieldsArray.id}
    {$res.dataForm.formFieldsArray.rTypeId}
    <div style="">
      {$res.dataForm.formFieldsArray.published}
      {foreach $langAvailableIds as $lang}
        {$res.dataForm.formFieldsArray["urlAlias_$lang"]}
      {/foreach}
      {$res.dataForm.formFieldsArray.externalUrl}
    </div>
    {foreach $langAvailableIds as $lang}
      {$res.dataForm.formFieldsArray["title_$lang"]}
    {/foreach}
    {$res.dataForm.formFieldsArray.rExtFile_author}

    {$res.dataForm.formFieldsArray.rExtFile_file}
    <label class="resImageMoreInfo">*{t}Files up to 5MB can be upload in JPG and PNG format{/t}</label>

    {$res.dataForm.formFieldsArray.image}
    <label class="resImageMoreInfo">*{t}If not uploaded, an automatic thumbnail will be created{/t}</label>

    {$res.dataForm.formFieldsArray.submit}


  {$res.dataForm.formClose}
  {$res.dataForm.formValidations}

{/block}{*/content*}



<!-- /rExtFormBlock.tpl (rTypeFileFormModalBlock)  en admin module -->
