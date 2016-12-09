<div class="rExtView rExtViewAltDefault {$res.data.rTypeIdName} res_{$res.data.id}">
  <section class="gzzSec">
    <div class="container">

      <h1>{$res.data.title|escape:'htmlall'}</h1>

      {if $res.data.mediumDescription}
        <div class="mDescription">
          {$res.data.mediumDescription}
        </div>
      {/if}

      {if $res.data.content}
        <div class="content">
          {$res.data.content}
        </div>
      {/if}

    </div>
  </section>
</div>
