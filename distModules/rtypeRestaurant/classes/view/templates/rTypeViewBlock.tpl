<!-- rTypeViewBlock.tpl en rtypeRestaurant module -->

<p> --- rTypeViewBlock.tpl en rtypeRestaurant module --- </p>

<div class="resViewBlock">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

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

  <div class="content">
    <label for="content" class="cgmMForm">{t}Content{/t}</label>
    {$content}
  </div>

  <div class="image cgmMForm-fileField ">
    <label for="imgResource" class="cgmMForm">{t}Image{/t}</label>
    <style type="text/css">.cgmMForm-fileField img { height: 100px }</style>
    {$image}
  </div>

  <div class="rTypeRestaurant">
    <p> --- rTypeRestaurant Ext --- </p>
    <div class="rTypeRestaurant accommodation">
      {$rextEatAndDrink}
    </div>
  </div>

</div>

<!-- /rTypeViewBlock.tpl en rtypeRestaurant module -->


