{extends file="primary.tpl"}


{block name="headClientIncludes" append}
  <script rel="false" type="text/javascript" src="/media/js/resource.js"></script>
{/block}

{block name="headTitle" append}{$title404}{/block}

{block name="bodyContent"}
  <div class="container">
    <div class="content404">
      <h1>404</h1>
      <h3>{t}La página indicada no existe.{/t}</h3>
      <p>{t}Puede usar los enlaces de la parte superior e inferior para moverse a las distintas secciones de la web.{/t}</p>
      
    </div>
  </div>
{/block}
