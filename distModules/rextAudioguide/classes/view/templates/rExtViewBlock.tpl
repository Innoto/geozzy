<div class="audioguideBlock">
    <div class="row row-eq-height row-eq-height-vertical-centered row-eq-height-horizontal-centered audioClosed">
      <div class="bg-icon">
        <i class="fa fa-volume-up" aria-hidden="true"></i>
      </div>
      <div class="texto">{t}Audioguide{/t}</div>
    </div>
    <div class="row row-eq-height row-eq-height-vertical-centered row-eq-height-horizontal-centered audioPlayer">
      <audio controls>
       <source src="{$cogumelo.publicConf.mediaHost}cgmlformfilews/{$rExt.data.audioFile.id}/{$rExt.data.audioFile.originalName}" type="{$rExt.data.audioFile.type}">
         Your browser does not support the audio element.
      </audio>
    </div>

    <!--<div class="title">{$rExt.data.audioFile.originalName}</div>-->
</div>
