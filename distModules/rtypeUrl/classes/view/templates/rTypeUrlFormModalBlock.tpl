
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
    {$res.dataForm.formFieldsArray.rExtUrl_author}
    {$res.dataForm.formFieldsArray.rExtUrl_urlContentType}

    <div class="cgmMForm-wrap">
      <label>{t}Link or embed{/t}</label>
      <select id="linkOrEmbed" class="cgmMForm-field">
        <option value="link">{t}External link{/t}</option>
        <option value="embed">{t}Embedable code{/t}</option>
      </select>
    </div>

    <div class="linkOrEmbed_link linkOrEmbedContainer">
      {$res.dataForm.formFieldsArray.rExtUrl_url}
    </div>
    <div class="linkOrEmbed_embed linkOrEmbedContainer">
      {$res.dataForm.formFieldsArray.rExtUrl_embed}
    </div>
    {$res.dataForm.formFieldsArray.image}
    <label class="resImageMoreInfo">*{t}If not uploaded, an automatic thumbnail will be created{/t}</label>

    {$res.dataForm.formFieldsArray.submit}

  {$res.dataForm.formClose}
  {$res.dataForm.formValidations}

{/block}{*/content*}



<!-- /rExtFormBlock.tpl (rTypeUrlFormModalBlock)  en admin module -->
