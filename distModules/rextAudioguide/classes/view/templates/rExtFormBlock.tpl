{$client_includes}
<div class="rExtAudioguide">
  <label class="cgmMForm cgmMForm-order">{t}Upload audio file (mp3){/t}</label>
  {foreach $cogumelo.publicConf.langAvailableIds as $lang}
      {$rExt.dataForm.formFieldsArray["rExtAudioguide_audioFile_$lang"]}
  {/foreach}
  <label class="cgmMForm cgmMForm-order">{t}Activation distance (m.){/t}</label>
  <input type="text" id="rExtAudioguide_distance" class="cgmMForm-field cgmMForm-field-rExtAudioguide_distance" form="resourceEdit" name="rExtAudioguide_distance" value="{$rExt.data.distance}">
</div>
