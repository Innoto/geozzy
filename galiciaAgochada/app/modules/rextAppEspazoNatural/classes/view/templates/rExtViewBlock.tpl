<p> --- rExtViewBlock.tpl en rextAppEspazoNatural module</p>

<div class="rExtAppEspazoNatural">

  <div class="appEspazoNaturalType">
    <label>{t}Espazo natural type{/t}</label>
    {if isset($rExt.data.appEspazoNaturalType)}
    <ul>
    {$l = $cogumelo.publicConf.C_LANG}
    {foreach from=$rExt.data.appEspazoNaturalType item=termInfo}
      <li>{$termInfo["name_$l"} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}
  </div>


</div>

<!-- /rExtViewBlock.tpl en rextAppEspazoNatural module -->
