<!-- rExtViewBlock.tpl en rextEatAndDrink module -->

<div class="rExtEatAndDrink">

<!--

  <p> --- rExtViewBlock.tpl en rextEatAndDrink module</p>

  <div class="reservationURL">
    <label>{t}Reservation URL{/t}</label>
    {$rExt.data.reservationURL|escape:'htmlall'}
  </div>

  <div class="reservationPhone">
    <label>{t}Reservation phone{/t}</label>
    {$rExt.data.reservationPhone|escape:'htmlall'}
  </div>

  <div class="capacity">
    <label>{t}Capacity{/t}</label>
    {if isset($rExt.data.capacity)}{$rExt.data.capacity|escape:'htmlall'}{/if}
  </div>

  <div class="averagePrice">
    <label>{t}Average Price{/t}</label>
    {if isset($rExt.data.averagePrice)}{$rExt.data.averagePrice|escape:'htmlall'}{/if}
  </div>

  <div class="eatanddrinkType">
    <label>{t}Restaurant type{/t}</label>
    {if isset($rExt.data.eatanddrinkType)}
    <ul>
    {foreach from=$rExt.data.eatanddrinkType item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}
  </div>

  <div class="eatanddrinkSpecialities">
    <label>{t}Specialities{/t}</label>
    {if isset($rExt.data.eatanddrinkSpecialities)}
    <ul>
    {foreach from=$rExt.data.eatanddrinkSpecialities item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}
  </div>

-->
</div>

<!-- /rExtViewBlock.tpl en rExtEatAndDrink module -->
