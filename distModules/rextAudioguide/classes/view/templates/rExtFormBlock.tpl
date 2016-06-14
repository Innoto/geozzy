{$client_includes}
<div class="rExtAudioguide formBlock">

  <input type="text" id="rextAudioguide_distance" name="rextAudioguide_distance" value="{$rExt.data.distance}" />

  {foreach $cogumelo.publicConf.langAvailableIds as $lang}
    {$rExt.dataForm.formFieldsArray["rExtAudioguide_audioFile_$lang"]}
  {/foreach}

</div>
