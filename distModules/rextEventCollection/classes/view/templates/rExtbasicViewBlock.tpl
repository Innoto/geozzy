<div class="basicCollectionView">

{if isset($rExt.data.events)}
  <div class="row">
  {foreach $rExt.data.events as $eventDate}
    <div class="event col-md-3 col-sm-6 col-xs-12">
      <div class="box">
        <div class="eventImg">
          <img class="img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$eventDate.resource.image}/listEvent/{$eventDate.resource.image}.jpg"/>
        </div>
        <div class="eventContent">
          <div class="eventDate">
            <p>{$eventDate.event.formatedDate.l}, {$eventDate.event.formatedDate.j} de {$eventDate.event.formatedDate.F}</p>
          </div>
          <div class="eventTitle">
            {$eventDate.resource.title}
          </div>
          <div class="eventTime">
            <i class="fa fa-clock-o" aria-hidden="true"></i>{$eventDate.event.formatedDate.time} h.
          </div>
        </div>
      </div>
    </div>
  {/foreach}
</div>
{/if}

</div>
