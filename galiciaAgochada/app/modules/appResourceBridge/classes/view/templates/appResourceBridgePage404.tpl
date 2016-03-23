{extends file="primary.tpl"}


{block name="headClientIncludes" append}
  <script rel="false" type="text/javascript" src="{$cogumelo.publicConf.media}/js/resource.js"></script>
{/block}

{block name="headTitle" append}{$title404}{/block}

{block name="bodyContent"}

  <div class="container">
    <img src="/bd_low.png" style="float:right;">
    <div class="content404">
      <br><br><br>
      <h1><i class="fa fa-smile-o"></i> Parabéns!</h1>
      <h3>{t}La página indicada no existe.{/t}</h3>
      <p>{t}Ya puede anotar horas por todo el tiempo perdido en el visionado.{/t}</p>

    </div>
  </div>
{/block}
