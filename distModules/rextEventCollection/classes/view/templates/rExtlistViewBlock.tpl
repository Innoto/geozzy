<div class="listCollectionView accordion" id="accordion2">

{if isset($rExt.data.events)}

  {$eventsByDate = array()}
  {foreach $rExt.data.events as $key=>$res}
    {$eventsByDate[$res.event.formatedDate.initDate]['id'] = $res.event.resource}
    {$eventsByDate[$res.event.formatedDate.initDate]['formatedDate'] = $res.event.formatedDate}
    {$eventsByDate[$res.event.formatedDate.initDate]['relatedResource'] = $res.event.relatedResource}
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
            {$eventDate.formatedDate.l}, <span class="dateContent">{$eventDate.formatedDate.j} de {$eventDate.formatedDate.F}</span>
        </a>
      </div>
      <div id="ele{$eventDate.id}" class="accordion-body collapse {if $eventDate@first}in{/if}">
        {foreach $eventDate.data as $i => $elm}
        <div class="extendedData accordion-inner">
          <p>
            <span class="eventTime">
              {$elm.event.formatedDate.time} h.
            </span>
            <span class="eventTitle">
              {$elm.resource.title}
            </span>
          </p>
          <div class="row">
            <div class="col-md-7">
              <div class="eventDescription">
                <p>{$elm.resource.mediumDescription}</p>
              </div>
              {if $eventDate.relatedResource}
              <a href="{$eventDate.relatedResource}" class="btn moreInfo">
                <i class="fa fa-plus" aria-hidden="true"></i> {t}More information{/t}
              </a>
              {/if}
              {if $elm.resource.urlAlias}
              <a href="{$elm.resource.urlAlias}" class="btn moreInfo">
                <i class="fa fa-plus" aria-hidden="true"></i> {t}More information{/t}
              </a>
              {/if}
            </div>
            <div class="eventImg col-md-5">
              <img class="img-fluid" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$elm.resource.image}/basicEvent/{$elm.resource.image}.jpg"/>
            </div>
          </div>
        </div>
        {/foreach}
      </div>
    </div>
  {/foreach}

{/if}

</div>
