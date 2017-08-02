{extends file="primary.tpl"}


{block name="headTitle" append}{$title}{/block}

{block name="bodyContent"}
  <div class="container">
    <div class="content404">
      <h1>Error 404: {$title}</h1>
      <br><br>
      <h4>{t}La página indicada no existe.{/t}</h4>
      <br><br><br><br>
    </div>
  </div>
{/block}
