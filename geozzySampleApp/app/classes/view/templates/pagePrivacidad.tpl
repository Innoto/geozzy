
{assign scope="global" var="bodySection" value="{$res.data.idName}"}

<section class="gzzSec secPagePrivacidad">
  <div class="container">
    <h1>{$res.data.title}</h1>
    <div class="resumen">
      {if $res.data.mediumDescription}
        {$res.data.mediumDescription}
      {/if}
    </div>
    <div class="content styleContent">
      {if $res.data.content}
        {$res.data.content}
      {/if}
    </div>
  </div>

  <div class="endSecPagePrivacidad" style="height:0px"></div>
</section>
