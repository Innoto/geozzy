<div class="calendarCollectionView accordion" id="accordion2">

  {$eventsByDate = array()}
  {foreach $rExt.data.events as $key=>$res}
    {$eventsByDate[$res.event.formatedDate.initDate]['id'] = $res.event.resource}
    {$eventsByDate[$res.event.formatedDate.initDate]['formatedDate'] = $res.event.formatedDate}
    {$eventsByDate[$res.event.formatedDate.initDate]['data'][] = $res}
  {/foreach}


  {foreach $eventsByDate as $k=>$eventDate}
    <div class="event accordion-group">
      <div class="accordion-heading row">
        <a class="date accordion-toggle col-md-4" data-toggle="collapse" data-parent="#accordion2" href=".ele{$eventDate.id}">
          <i class="fa fa-caret-right" aria-hidden="true"></i>
          {$eventDate.formatedDate.l}, {$eventDate.formatedDate.j} de {$eventDate.formatedDate.F}
        </a>
      </div><hr/>
      <div class="ele{$eventDate.id} accordion-body collapse {if $eventDate@first}in{/if} clearfix">
        {foreach $eventDate.data as $i => $elm}
        <div class="extendedData accordion-inner">
          <div class="externalBox col-md-4 col-sm-6 col-xs-12">
            <div class="box">
              <div class="eventImg">
                <img src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$elm.resource.image}/calendarEvent/{$elm.resource.image}.jpg"/>
              </div>
              <div class="eventText">
                <div class="eventTitle">
                  <p><b>{$elm.resource.title}</b></p>
                </div>
                <div class="time">
                  <p><b>{$elm.event.formatedDate.time} h.</b></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        {/foreach}
      </div>
    </div>
  {/foreach}

</div>
