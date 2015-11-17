<!-- tplTwoColsPage.tpl en rExtView module -->

<p> --- tplTwoColsPage.tpl en rExtView module --- </p>

<div class="resViewBlock tplTwoColsPage">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="col1" style="width: 45%; float: left">

    <div class="title">
      <label class="cgmMForm">{t}Title{/t}</label>
      {$title|escape:'htmlall'}
    </div>

    <div class="shortDescription">
      <label class="cgmMForm">{t}Short description{/t}</label>
      {$shortDescription|escape:'htmlall'}
    </div>

    <div class="mediumDescription">
      <label for="mediumDescription" class="cgmMForm">{t}Medium description{/t}</label>
      {$mediumDescription}
    </div>

  </div>
  <div class="col2" style="width: 45%; float: left">

    <div class="content">
      <label for="content" class="cgmMForm">{t}Content{/t}</label>
      {$content}
    </div>

    <div class="image cgmMForm-fileField">
      <label for="imgResource" class="cgmMForm">{t}Image{/t}</label>
      <style type="text/css">.cgmMForm-fileField img { height: 100px }</style>
      {$image}
    </div>

    {if isset($externalUrl)}
    <div class="externalUrl">
      <label for="externalUrl" class="cgmMForm">{t}External Url{/t}</label>
      {$externalUrl|escape:'htmlall'}
    </div>
    {/if}

    {if isset($collections)}
    <div class="collections">
      <label for="collections" class="cgmMForm">{t}Collections{/t}</label>
      {$collections}
    </div>
    {/if}

  </div>

</div>

<!-- /tplTwoColsPage.tpl en geozzy module -->



