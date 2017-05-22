<section class="gzzSec">
  <div class="container">
    <h1>{$res.data.title}</h1>
    {if $res.data.hashValid}
      <h3>{t}Enter a new password:{/t}</h3>
      {$blockContent}
    {else}
      <h3>{t}Your url has expired. Please restart the password recovery process{/t}</h3>
    {/if}
  </div>
</section>
