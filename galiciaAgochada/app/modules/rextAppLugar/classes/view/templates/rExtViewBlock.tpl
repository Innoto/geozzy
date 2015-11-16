<!-- rExtViewBlock.tpl en rextAppLugar module -->

<p> --- rExtViewBlock.tpl en rextAppLugar module</p>

<div class="rExtAppLugar">

  <div class="rextAppLugarType">
    <label>{t}Lugar type{/t}</label>
    {if isset($rExt.data.rextAppLugarType)}
    <ul>
    {foreach from=$rExt.data.rextAppLugarType item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}

  </div>


</div>

<!-- /rExtViewBlock.tpl en rextAppLugar module -->
