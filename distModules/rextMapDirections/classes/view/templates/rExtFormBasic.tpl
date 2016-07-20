<!-- rExtFormBasic.tpl en rextMapDirections module -->

<div class="rextMapDirections formBlock formBasic">

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

<!-- /rExtFormBasic.tpl en rextMapDirections module -->
