
{extends file="admin///adminPanel.tpl"}
{block name="content"}
  <style>
    label { display: block; }
    .cgmMForm-field { max-width: none !important; }
  </style>
  <script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/admin/js/adminResourceTypeMultimedia.js"></script>

  {$res.dataForm.formOpen}
    {$res.dataForm.formFieldsArray.cgIntFrmId}

    {foreach $cogumelo.publicConf.langAvailableIds as $lang}
      {$res.dataForm.formFieldsArray["title_$lang"]}
    {/foreach}
    {$res.dataForm.formFieldsArray.rExtFile_author}

    {$res.dataForm.formFieldsArray.rExtFile_file}
    <label class="resImageMoreInfo">*{t}Files up to 8MB can be upload in JPG and PNG format{/t}</label>

    {$res.dataForm.formFieldsArray.image}
    <label class="resImageMoreInfo">*{t}If not uploaded, an automatic thumbnail will be created{/t}</label>

    {$res.dataForm.formFieldsArray.submit}


  {$res.dataForm.formClose}
  {$res.dataForm.formValidations}

{/block}{*/content*}



<!-- /rExtFormBlock.tpl (rTypeFileFormModalBlock)  en admin module -->
