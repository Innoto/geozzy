<!-- rExtFormBasic.tpl en rExtContact module -->

<div class="rExtContact formBlock formBasic">
{$rExt.dataForm.formFieldsArray['rExtSocialNetwork_activeFb']}
{foreach $textFb as $text}
  {$rExt.dataForm.formFieldsArray[$text]}
{/foreach}
<div class="defaultFb-box">
  {$defaultFb[$cogumelo.publicConf.C_LANG]}
</div>
{$rExt.dataForm.formFieldsArray['rExtSocialNetwork_activeTwitter']}
{foreach $textTwitter as $text}
  {$rExt.dataForm.formFieldsArray[$text]}
{/foreach}
<div class="defaultFb-box">{$default_textTwitter}</div>
</div>

<!-- /rExtFormBasic.tpl en rExtContact module -->
