<div class="listCollectionView accordion" id="accordion2">

{if isset($rExt.data.events)}

  {$eventsByDate = array()}
  {foreach $rExt.data.events as $key=>$res}
    {$eventsByDate[$res.event.formatedDate.initDate]['id'] = $res.event.resource}
    {$eventsByDate[$res.event.formatedDate.initDate]['formatedDate'] = $res.event.formatedDate}
    {$eventsByDate[$res.event.formatedDate.initDate]['data'][] = $res}
  {/foreach}


  {foreach $eventsByDate as $k=>$eventDate}
    <div class="event accordion-group">
      <div class="accordion-heading">
        <a class="date accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#ele{$eventDate.id}">
            {if $eventDate@first}
              <i class="arrow arrow-down fa fa-caret-down" aria-hidden="true"></i>
              <i style="display:none;" class="arrow arrow-right fa fa-caret-right" aria-hidden="true"></i>
            {else}
              <i style="display:none;" class="arrow arrow-down fa fa-caret-down" aria-hidden="true"></i>
              <i class="arrow arrow-right fa fa-caret-right" aria-hidden="true"></i>
            {/if}

          <div class="dateContent">
            {$eventDate.formatedDate.l}, {$eventDate.formatedDate.j} de {$eventDate.formatedDate.F}
          </div>
        </a>
      </div>
      <div id="ele{$eventDate.id}" class="accordion-body collapse {if $eventDate@first}in{/if}">
        {foreach $eventDate.data as $i => $elm}
        <div class="extendedData accordion-inner row">

          <div class="eventTime col-md-1">
            <p><b>{$elm.event.formatedDate.time} h.</b></p>
          </div>
          <div class="eventTitle col-md-11">
            <p><b>{$elm.resource.title}</b></p>
          </div>

          <div class="eventDescription col-md-7">
            <p>{$elm.resource.mediumDescription}</p>
          </div>
          <img class="col-md-5" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$elm.resource.image}/basicEvent/{$elm.resource.image}.jpg"/>

        </div>
        {/foreach}
      </div>
    </div>
  {/foreach}

{/if}

</div>
