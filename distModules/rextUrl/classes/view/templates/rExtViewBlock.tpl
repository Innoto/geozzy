<!-- rExtViewBlock.tpl en rExtUrl module -->

<div class="rExtUrl">

  <!--
  {$rExt.data.urlContentType|var_dump}
  -->

  {if isset($rExt.data.embed) && $rExt.data.embed ne ''}

    <div class="embed">
      {$rExt.data.embed}
    </div>

  {else}

    <div class="urlLink">
      <a href="{$rExt.data.url|default:''|escape:'htmlall'}" target="_blank">{$rExt.data.url|default:''|escape:'htmlall'}</a>
    </div>

  {/if}

  {if isset($rExt.data.author) && $rExt.data.author ne ''}
  <div class="author">
    <label>{t}Author{/t}</label>
    {$rExt.data.author|escape:'htmlall'}
  </div>
  {/if}

</div>

<!-- /rExtViewBlock.tpl en rExtUrl module -->
