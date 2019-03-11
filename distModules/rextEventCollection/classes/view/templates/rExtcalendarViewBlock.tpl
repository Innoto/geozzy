<div class="calendarCollectionView accordion" id="accordion2">

{if isset($rExt.data.events)}
  {$eventsByDate = array()}
  {foreach $rExt.data.events as $key=>$res}
    {$eventsByDate[$res.event.formatedDate.initDateFirst]['id'] = $res.event.resource}
    {$eventsByDate[$res.event.formatedDate.initDateFirst]['formatedDate'] = $res.event.formatedDate}
    {$eventsByDate[$res.event.formatedDate.initDateFirst]['relatedResource'] = $res.event.relatedResource}
    {$eventsByDate[$res.event.formatedDate.initDateFirst]['data'][] = $res}
  {/foreach}


  {foreach $eventsByDate as $k=>$eventDate}
    <div class="event accordion-group">
      <div class="accordion-heading row">
        <a class="date accordion-toggle col-md-4" data-toggle="collapse" data-parent="#accordion2" href="#ele{$eventDate.id}">

          {if $eventDate@first}
            <i class="arrow arrow-down fas fa-caret-down" aria-hidden="true"></i>
            <i style="display:none;" class="arrow arrow-right fas fa-caret-right" aria-hidden="true"></i>
          {else}
            <i style="display:none;" class="arrow arrow-down fas fa-caret-down" aria-hidden="true"></i>
            <i class="arrow arrow-right fas fa-caret-right" aria-hidden="true"></i>
          {/if}
          {$eventDate.formatedDate.l}, <span class="dateContent">{$eventDate.formatedDate.j} de {$eventDate.formatedDate.F}</span>
        </a>
      </div><hr/>
      <div id="ele{$eventDate.id}" class="accordion-body collapse {if $eventDate@first}in{/if} clearfix">
        {foreach $eventDate.data as $i => $elm}
        <div class="extendedData accordion-inner">
          <div class="externalBox col-md-4 col-sm-6 col-xs-12">
            <div class="box">
              <div class="eventImg">
                <img class="img-fluid" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$elm.resource.image}/calendarEvent/{$elm.resource.image}.jpg"/>
              </div>
              <div class="eventText">

                <div class="eventTitle">
                  <span>{$elm.event.formatedDate.time} h.</span>{$elm.resource.title}</p>
                </div>

                <div class="eventDescription">
                  {$elm.resource.mediumDescription}
                </div>

                {if isset($eventDate.relatedResource) && $eventDate.relatedResource!==0}
                <a href="{$eventDate.relatedResource}" class="btn moreInfo">
                  <i class="fas fa-plus" aria-hidden="true"></i> {t}More information{/t}
                </a>
                {/if}
                {if isset($elm.resource.urlAlias)}
                <a href="{$elm.resource.urlAlias}" class="btn moreInfo">
                  <i class="fas fa-plus" aria-hidden="true"></i> {t}More information{/t}
                </a>
                {/if}

              </div>
            </div>
          </div>
        </div>
        {/foreach}
      </div>
    </div>
  {/foreach}

{/if}

</div>
