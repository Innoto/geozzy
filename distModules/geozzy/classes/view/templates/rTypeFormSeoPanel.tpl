{extends file="admin///adminPanel.tpl"}

{block name="content"}

<script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/geozzy/js/qrcodeGenerator.js"></script>

{if isset($prevContent)}
  {$prevContent}
{/if}

{if isset($prevFormFieldsNames)}
  {foreach $prevFormFieldsNames as $name}
    {$res.dataForm.formFieldsArray[$name]}
  {/foreach}
{/if}

{if isset($formFieldsNames)}
  {foreach $formFieldsNames as $name}
    {$res.dataForm.formFieldsArray[$name]}
  {/foreach}
{/if}

{if isset($blockContent)}
  {$blockContent}
{/if}

{if isset($postFormFieldsNames)}
  {foreach $postFormFieldsNames as $name}
    {$res.dataForm.formFieldsArray[$name]}
  {/foreach}
{/if}

{if isset($postContent)}
  {$postContent}
{/if}

{/block}{*/content*}
