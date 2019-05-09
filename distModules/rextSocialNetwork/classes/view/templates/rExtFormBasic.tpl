<!-- rExtFormBasic.tpl en rExtContact module -->
<div class="rExtSocialNetwork formBlock formBasic">
  <div class="network facebook">
    {$rExt.dataForm.formFieldsArray['rExtSocialNetwork_activeFb']}
    {foreach $textFb as $text}
      {$rExt.dataForm.formFieldsArray[$text]}
    {/foreach}
    <div class="defaultBox">
      <div class="intro">
        <p>{t}Si no se especifica ningún texto, se utilizará por defecto el texto en el recuadro.{/t} </p>
        <p>{t}Para mostrar el nombre del recurso se debe usar #TITLE# y para compartir la url de la página se debe poner #URL#:{/t}</p>
      </div>
    </div>
  </div>
  <div class="network twitter">
    {$rExt.dataForm.formFieldsArray['rExtSocialNetwork_activeTwitter']}
    {foreach $textTwitter as $text}
      {$rExt.dataForm.formFieldsArray[$text]}
    {/foreach}
    <div class="defaultBox">
      <p>{t}Si no se especifica ningún texto, se utilizará por defecto el texto en el recuadro.{/t} </p>
      <p>{t}Para mostrar el nombre del recurso se debe usar #TITLE# y para compartir la url de la página se debe poner #URL#:{/t}</p>
    </div>
  </div>
  {* <div class="network gplus">
    {$rExt.dataForm.formFieldsArray['rExtSocialNetwork_activeGplus']}
    <div class="defaultBox">
      <p>{t}Google+ compartira automáticamente la URL del recurso{/t}</p>
    </div>
  </div> *}
</div>

<!-- /rExtFormBasic.tpl en rExtContact module -->
