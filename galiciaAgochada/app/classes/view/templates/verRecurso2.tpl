{extends file="default.tpl"}

{block name="headCssIncludes" append}
<style type="text/css">
  label { color:green; padding: 5px 0; }
  .resource div { color:red; padding: 5px 20px; }
</style>
{/block}


{block name="bodyContent"}
<!-- verRecurso2.tpl en app de Geozzy -->

  <h2 style="color:#30494E;">GEOZZY APP - {t}View Resource{/t}</h2>
  <h3>{t}Resource{/t}</h3>
  <div class="resource">
    {block name="resourceBlock"}{/block}
  </div>

<!-- /verRecurso2.tpl en app de Geozzy -->
{/block}
