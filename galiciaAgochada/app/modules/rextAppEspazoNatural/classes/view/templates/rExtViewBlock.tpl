<p> --- rExtViewBlock.tpl en rextAppEspazoNatural module</p>

<div class="rExtAppEspazoNatural">

  <div class="appEspazoNaturalType">
    <label>{t}Espazo natural type{/t}</label>
    {if isset($rExt.data.appEspazoNaturalType)}
    <ul>
    {foreach from=$rExt.data.appEspazoNaturalType item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}
  </div>


</div>

<!-- /rExtViewBlock.tpl en rextAppEspazoNatural module -->
