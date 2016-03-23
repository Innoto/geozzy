<!-- rExtViewBlock.tpl en rextAppFesta module -->

<p> --- rExtViewBlock.tpl en rextAppFesta module</p>

<div class="rExtAppFesta">

  <div class="rextAppFestaType">
    <label>{t}Festa type{/t}</label>
    {if isset($rExt.data.rextAppFestaType)}
    <ul>
    {foreach from=$rExt.data.rextAppFestaType item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}

  </div>


</div>

<!-- /rExtViewBlock.tpl en rextAppFesta module -->
