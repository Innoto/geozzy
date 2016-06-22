<div class="audioguideBlock">

    <div class="col-md-4">
      <div class="title ">
        <i class="fa fa-headphones" aria-hidden="true"></i>
        <div class="text">{t}Audioguide{/t}</div>
      </div>
    </div>
    <div class="audioguide col-md-8">
      <audio controls>
       <source src="{$cogumelo.publicConf.mediaHost}cgmlformfilews/{$rExt.data.audioFile.id}/{$rExt.data.audioFile.originalName}" type="{$rExt.data.audioFile.type}">
         Your browser does not support the audio element.
      </audio>
    </div>

    <!--<div class="title">{$rExt.data.audioFile.originalName}</div>-->
</div>
