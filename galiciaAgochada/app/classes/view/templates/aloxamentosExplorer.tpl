{extends file="primary.tpl"}

{block name="headJsIncludes" append}
  <script src="https://maps.googleapis.com/maps/api/js"></script>
{/block}

{block name="bodyContent"}
  <div class="titleBar">
    <div class="container">
      <img class="img-responsive" alt="Paisaxes Espectaculares" src="/media/img/aloxamentosIcon.png"></img>
      <h1>Aloxamentos con encanto</h1>
    </div>
  </div>
  <div class="aloxamentosExplorer">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}
