{extends file="primary.tpl"}

{block name="headContent"}
  <h2><i class="fa fa-optin-monster fa-2x"></i> GALICIA AGOCHADA </h2>
{/block}

{block name="bodyContent"}
  <div style="background:#34536C; width:100%; height:100vh;">
    <h2 style="position:absolute; top:40%; left:34%; color:#fff;"> <i class="fa fa-tree fa-3x"></i> GALICIA AGOCHADA </h2>
  </div>
  {literal}
  <script>
    $( document ).ready(function() {
      var timedbuger = new TimeDebuger({debug:true});
    });

  </script>
  {/literal}
{/block}
