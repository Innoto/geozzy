{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script src="https://maps.googleapis.com/maps/api/js"></script>
{/block}

{block name="bodyContent"}
  <div class="titleBar">
    <div class="container">
      <img class="iconTitleBar img-responsive" alt="Paisaxes Espectaculares" src="/media/img/aloxamentosIcon.png"></img>
      <h1>{t}Aloxamentos con encanto{/t}</h1>
    </div>
  </div>
  <div class="aloxamentosExplorer">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}


{block name="footerContent"}

{/block}
