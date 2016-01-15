<!-- rExtFormBasic.tpl en rExtContact module -->

<div class="rExtContact formBlock formBasic">

{$rExt.dataForm.formFieldsArray['rExtContact_address']}
{$rExt.dataForm.formFieldsArray['rExtContact_city']}
{$rExt.dataForm.formFieldsArray['rExtContact_cp']}
{$rExt.dataForm.formFieldsArray['rExtContact_province']}
{$rExt.dataForm.formFieldsArray['rExtContact_phone']}
{$rExt.dataForm.formFieldsArray['rExtContact_email']}
{$rExt.dataForm.formFieldsArray['rExtContact_url']}
{foreach $timetable as $time}
  {$rExt.dataForm.formFieldsArray[$time]}
{/foreach}

</div>

<!-- /rExtFormBasic.tpl en rExtContact module -->
