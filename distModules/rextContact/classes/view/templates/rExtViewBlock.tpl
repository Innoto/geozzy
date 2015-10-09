<!-- rExtViewBlock.tpl en rExtAccommodation module -->

<p> --- rExtViewBlock.tpl en rExtContact module</p>

<div class="rExtContact">

  <div class="address">
    <label>{t}Address{/t}</label>
    {$rExtContact_address|escape:'htmlall'}
  </div>

  <div class="city">
    <label>{t}City{/t}</label>
    {$rExtContact_city|escape:'htmlall'}
  </div>

  <div class="cp">
    <label>{t}Postal code{/t}</label>
    {$rExtContact_cp|escape:'htmlall'}
  </div>

  <div class="province">
    <label>{t}Province{/t}</label>
    {$rExtContact_province|escape:'htmlall'}
  </div>

  <div class="email">
    <label>{t}contact e-mail{/t}</label>
    {$rExtContact_email|escape:'htmlall'}
  </div>

  <div class="directions">
    <label>{t}How to arrive{/t}</label>
    {$rExtContact_directions|escape:'htmlall'}
  </div>

  <!-- taxonomías -->

  <!-- coleciones -->

</div>

<!-- /rExtViewBlock.tpl en rExtContact module -->
