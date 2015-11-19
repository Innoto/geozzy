<!-- rExtViewBlock.tpl en rExtAccommodation module -->
<div class="rExtAccommodation">
  
<!--
  <p> --- rExtViewBlock.tpl en rExtAccommodation module</p>

  <div class="reservationURL">
    <label>{t}Reservation URL{/t}</label>
    {$rExt.data.reservationURL|escape:'htmlall'}
  </div>

  <div class="reservationPhone">
    <label>{t}Reservation phone{/t}</label>
    {$rExt.data.reservationPhone|escape:'htmlall'}
  </div>

  <div class="accomodationCategory">
    <label>{t}Accommodation category{/t}</label>
    {if isset($rExt.data.accommodationCategory)}
    <ul>
    {foreach from=$rExt.data.accommodationCategory item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}
  </div>

  <div class="accomodationServices">
    <label>{t}Accommodation services{/t}</label>
    {if isset($rExt.data.accommodationServices)}
    <ul>
    {foreach from=$rExt.data.accommodationServices item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}
  </div>

  <div class="accomodationFacilities">
    <label>{t}Accommodation facilities{/t}</label>
    {if isset($rExt.data.accommodationFacilities)}
    <ul>
    {foreach from=$rExt.data.accommodationFacilities item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}
  </div>

  <div class="accomodationBrand">
    <label>{t}Accommodation brand{/t}</label>
    {if isset($rExt.data.accommodationBrand)}
    <ul>
    {foreach from=$rExt.data.accommodationBrand item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}
  </div>

  <div class="accomodationUsers">
    <label>{t}Accommodation users profile{/t}</label>
    {if isset($rExt.data.accommodationUsers)}
    <ul>
    {foreach from=$rExt.data.accommodationUsers item=termInfo}
      <li>{$termInfo.name_es} ({$termInfo.id})</li>
    {/foreach}
    </ul>
    {/if}
  </div>
-->

</div>

<!-- /rExtViewBlock.tpl en rExtAccommodation module -->
