{assign scope="global" var="bodySection" value="{$res.data.idName}"}

<section class="gzzSec secPageGeneric">
  <div class="container">
    <h1 class="gzzh1h2">{$res.data.title}</h1>
    <div class="content">
      {if $res.data.content}
        {$res.data.content}
      {/if}
    </div>
  </div>
</section>
