<script type="text/javascript">
  var audioGuideTagSource = '<audio id="myPlayer" controls><source src="{$cogumelo.publicConf.mediaHost}cgmlformfilews/{$rExt.data.audioFile.id}/{$rExt.data.audioFile.originalName}" type="{$rExt.data.audioFile.type}">'+
       'Your browser does not support the audio element.</audio>';
</script>

<div class="audioguideBlock">
    <div class="audioClosed">
      <div class="bg-icon">
        <i class="fas fa-volume-up" aria-hidden="true"></i>
      </div>
      <div class="texto">{t}Audioguide{/t}</div>
    </div>
    <div class="audioPlayer">

    </div>

    <!--<div class="title">{$rExt.data.audioFile.originalName}</div>-->
</div>
