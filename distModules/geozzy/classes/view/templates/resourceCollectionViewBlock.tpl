<!-- resourceCollectionViewBlock.tpl en geozzy module -->

<p> --- resourceCollectionViewBlock.tpl en geozzy module --- </p>

<div class="resCollViewBlock">

  <div class="title">
    <label class="cgmMForm">{t}Title{/t}</label>
    {$title|escape:'htmlall'}
  </div>

  <div class="shortDescription">
    <label class="cgmMForm">{t}Short description{/t}</label>
    {$shortDescription|escape:'htmlall'}
  </div>

  <div class="image cgmMForm-fileField">
    <label for="imgResource" class="cgmMForm">{t}Image{/t}</label>
    <style type="text/css">.cgmMForm-fileField img { height: 100px }</style>
    {$image}
  </div>

  <div class="collResources">
    <label for="collResources" class="cgmMForm">{t}Collection resources{/t}</label>
    {$collectionResources}
  </div>

</div>

<!-- /resourceCollectionViewBlock.tpl en geozzy module -->
