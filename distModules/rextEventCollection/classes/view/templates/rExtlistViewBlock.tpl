<div class="listCollectionView">

  {foreach $rExt.data.events as $eventDate}
    <div class="event col-md-3">
      <div class="box">
        <div class="eventImg">
          <img src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$eventDate.resource.image}/listEvent/{$eventDate.resource.image}.jpg"/>
        </div>
        <div class="eventDate">
          <p>{$eventDate.event.formatedDate.l}, {$eventDate.event.formatedDate.j} de {$eventDate.event.formatedDate.F}</p>
        </div>
        <div class="eventTitle">
          {$eventDate.resource.title}
        </div>
        <div class="eventTime">
          {$eventDate.event.formatedDate.time} h.
        </div>
      </div>
    </div>

  {/foreach}

</div>
