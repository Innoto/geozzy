<div class="audioguideBlock">
    <div class="title">
      <i class="fa fa-headphones" aria-hidden="true"></i>
      {t}Audioguide{/t}
    </div>

    <div class="audioguide">
      <audio controls>
       <source src="{$cogumelo.publicConf.mediaHost}cgmlformfilews/{$rExt.data.audioFile.id}/{$rExt.data.audioFile.originalName}" type="{$rExt.data.audioFile.type}">
         Your browser does not support the audio element.
      </audio>
    </div>
    <!--<div class="title">{$rExt.data.audioFile.originalName}</div>-->
</div>
