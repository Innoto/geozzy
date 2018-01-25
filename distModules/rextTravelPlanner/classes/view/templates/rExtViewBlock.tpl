<script type="text/template" id="travelPlannerInterfaceTemplate">
  <div class="travelPlanner">
    <div class="travelPlannerBar clearfix">
      <div class="travelPlannerDateButtons clearfix">
        <div class="travelPlannerDateBar"></div>
        <div class="travelPlannerButtonsBar clearfix">
          <div class="tp-gotoPlan">{t}Go to my plan{/t} <i class="fa fa-angle-right" aria-hidden="true"></i></div>
          <div class="tp-goAddtoPlan"><i class="fa fa-angle-left" aria-hidden="true"></i> {t}Add Places{/t}</div>
        </div>
      </div>
      <div class="travelPlannerFilterBar clearfix">
        <div class="mode mode1">
          <span>{t}Filtros :{/t}</span>
          <select class="filterByFavourites">
            <option value="*">{t}All{/t}</option>
            <option selected="selected" value="fav">{t}Favourites{/t}</option>
          </select>
          <select class="filterByRtype">
            <option value="*">{t}All Contents{/t}</option>
            <% _.each( rtypesFilters, function( elem ) { %>
              <option value="<%= elem.idName %>"><%= elem.name %></option>
            <% }); %>
          </select>
        </div>
        <div class="mode mode2">
          <ul class="days clearfix"></ul>
        </div>
      </div>
    </div>
    <div class="travelPlannerList">
      <div class="travelPlannerResources"></div>
    </div>
    <div class="travelPlannerPlan">
      <div class="travelPlannerPlanHeader"></div>
      <div class="travelPlannerPlanDaysContainer"></div>
    </div>
    <div class="travelPlannerMap">
      <div class="map" style="width:100%; height:100%;"></div>
    </div>
    <div class="travelPlannerMapPlan">
      <div class="map" style="width:100%; height:100%;"></div>
      <div class="mapFilterDay clearfix">
        <div class="filterDay-previous"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
        <div class="filterDay-current">{t}Day{/t}&nbsp;<span class="number">1</span></div>
        <div class="filterDay-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
      </div>
    </div>
  </div>
</script>

<script type="text/template" id="travelPlannerInterfaceMobileTemplate">
  <div class="travelPlannerMobile">
    <div class="travelPlannerBar clearfix">
      <div class="travelPlannerFilterBar clearfix">
        <div class="mode mode1">
          <select class="filterByFavourites">
            <option value="*">{t}All{/t}</option>
            <option selected="selected" value="fav">{t}Favourites{/t}</option>
          </select>
          <select class="filterByRtype">
            <option value="*">{t}All Contents{/t}</option>
            <% _.each( rtypesFilters, function( elem ) { %>
              <option value="<%= elem.idName %>"><%= elem.name %></option>
            <% }); %>
          </select>
        </div>
        <div class="mode mode2">
          <ul class="days clearfix"></ul>
        </div>
      </div>
    </div>
    <div class="travelPlannerList">
      <div class="travelPlannerResources"></div>
    </div>
    <div class="travelPlannerPlan">
      <div class="travelPlannerPlanHeader"></div>
      <div class="travelPlannerPlanDaysContainer"></div>
    </div>
    <div class="travelPlannerMapPlan fixVhChromeMobile">
      <div class="map" style="width:100%; height:100%;"></div>
      <div class="mapFilterDay clearfix">
        <div class="filterDay-previous"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
        <div class="filterDay-current">{t}Day{/t}&nbsp;<span class="number">1</span></div>
        <div class="filterDay-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
      </div>
    </div>

      <div class="tp-gotoMobilePlan"><i class="fa fa-list" aria-hidden="true"></i> {t}Plan{/t}</div>
      <div class="tp-gotoMobileList"><i class="fa fa-plus" aria-hidden="true"></i> {t}Add{/t}</div>
      <div class="tp-gotoMobileChangeDates"><i class="fa fa-calendar" aria-hidden="true"></i> {t}Dates{/t}</div>
      <!--<div class="tp-gotoMobileMap"><i class="fa fa-map-o" aria-hidden="true"></i> {t}Map{/t}</div>-->

  </div>
</script>

<script type="text/template" id="resourceItemTPTemplate">
  <div class="tpResourceItem" data-resource-id="<%- resource.id %>">
    <div class="image">
      <img class="img-responsive" src="/cgmlImg/<%- resource.image %>/travelPlannerList/<%- resource.image %>.jpg">
      <button class="addToPlan btn btn-primary"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></button>
      <a href="#resource/<%- resource.id %>">
        <button class="openResource btn btn-default"><i class="fa fa-search" aria-hidden="true"></i></i></button>
      </a>
      <% if (typeof(resource.defaultDuration) != "undefined") { %>
        <div class="duration"><%= resource.defaultDuration %></div>
      <% } %>
    </div>
    <div class="title"><%- resource.title %></div>
    <div class="description"><%- resource.shortDescription %></div>

  </div>
</script>

<script type="text/template" id="datesTPTemplate">
  <div class="datesTpContainer clearfix">
    <!--<div class="title">{t}When do you want to travel? {/t}</div>-->
    <div class="icon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
    <input type="text" id="checkTpDates" class="form-control" readonly>
  </div>
</script>
<!-- *************************** NESTABLE DAY LIST ************************************** -->
<script type="text/template" id="dayTPTemplate">
  <span class="anchor" id="plannerDay-<%- day.id %>"></span>
  <div class="plannerDay plannerDay-<%- day.id %>" data-day="<%- day.id %>">
    <div class="plannerDayHeader clearfix">
      <div class="dayTitle">{t}Day{/t}<span> <%- day.id+1 %><span> </div>
      <div class="infoDay"><div class="day"><%- day.dayName %></div><div class="date"><%- day.date %></div></div>
      <div class="infoRight clearfix">
        <div class="clearfix">
          <div class="infoTime"><i class="fa fa-clock-o" aria-hidden="true"></i> <span>-h --min</span></div>
          <div class="infoTimeTransport"></div>
        </div>
        <div class="showMap"><i class="fa fa-map-o" aria-hidden="true"></i>&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></div>
        <div class="optimizeDay"><i class="fa fa-magic " aria-hidden="true"></i>&nbsp;<span>{t}Optimize day{/t}</span></div>
      </div>
    </div>
    <div class="plannerDayPlanner gzznestable dd">
      <div class="dd-empty"></div>
    </div>
  </div>
</script>
<!-- *************************** NESTABLE ITEM ************************************** -->
<script type="text/template" id="resourcePlanItemTemplate">
  <li class="dd-item" data-id="<%- resource.serializedData %>">
    <div class="dd-item-container clearfix">
      <div class="dd-handle">
        <div class="iconHandle"><i class="fa fa-bars icon-handle"></i></div>
        <div class="image"><img class="resImageIcon" src="/cgmlImg/<%- resource.image %>/travelPlannerListIcon/<%- resource.image %>.jpg"></div>
      </div>
      <div class="dd-content">
        <div class="info clearfix">
          <div class="title"><%- resource.title %></div>
          <div class="time"><i class="fa fa-clock-o" aria-hidden="true"></i> <%- resource.timeFormated %></div>
          <div class="infoTimeRoute"></div>
        </div>
        <div class="nestableActions">
          <button class="btnEdit btn-icon btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></button>
          <button class="btnDelete btn-icon btn-danger"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
      </div>

    </div>
  </li>
</script>
<!-- *************************** NESTABLE ITEM MOBILE************************************** -->
<script type="text/template" id="resourcePlanItemMobileTemplate">
  <li class="dd-item" data-id="<%- resource.serializedData %>">
    <div class="dd-item-container clearfix">
      <div class="dd-handle">
        <div class="iconHandle"><i class="fa fa-bars icon-handle"></i></div>
        <div class="image"><img class="resImageIcon" src="/cgmlImg/<%- resource.image %>/travelPlannerListIconMobile/<%- resource.image %>.jpg"></div>
      </div>
      <div class="dd-content">
        <div class="info clearfix">
          <div class="title"><%- resource.title %></div>
          <div class="time"><i class="fa fa-clock-o" aria-hidden="true"></i> <%- resource.timeFormated %></div>
          <div class="infoTimeRoute"></div>
        </div>
        <div class="nestableActions">
          <button class="btnEdit btn-icon btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></button>
          <button class="btnDelete btn-icon btn-danger"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
      </div>

    </div>
  </li>
</script>
<!-- *************************** MODAL ITEM ************************************** -->
<script type="text/template" id="resourceTpModalTemplate">
  <div class="resourceTp" data-resource-id="<%- resource.id %>">
    <div class="image"><img class="img-responsive" src="/cgmlImg/<%- resource.image %>/travelPlannerListBig/<%- resource.image %>.jpg"></div>
    <div class="title"><%- resource.title %></div>

    <form>
      <div class="labelText">{t}Select date to visit this place or event{/t}</div>
      <ul class="selectorDays clearfix">
        <% _.each(dates, function(i) { %>
          <li class="day-<%= i.id %> <% if(i.inPlan){ %> inPlan <% } %>" data-day="<%= i.id %>">
            <div class="dayName"><%= i.dayName %></div>
            <div class="dayNumber"><%= i.day %></div>
            <div class="month"><%= i.month %></div>
          </li>
        <% }); %>
      </ul>

      <div class="labelText">{t}How long do you want to visit this place?{/t}</div>
      <div class="hoursContainer row clearfix">
        <div class="col-xs-6">
          <label for="hlong-hour">{t}hours{/t}</label>
          <input type="number" name="hlong-hour" class="hlong-hour" placeholder="{t}hours{/t}" min="0" max="23" value="<%- resource.defaultDurationH %>">
        </div>
        <div class="col-xs-6">
          <label for="hlong-minutes">{t}minutes{/t}</label>
          <input type="number" name="hlong-minutes" class="hlong-minutes" placeholder="{t}minutes{/t}" min="0" max="59" value="<%- resource.defaultDurationM %>">
        </div>
      </div>
      <div class="buttonActions">
        <button type="button" class="cancelAdd btn btn-warning">{t}Cancel{/t}</button>
        <button type="button" class="acceptAdd btn btn-success">{t}Add to plan{/t}</button>
      <div>
    </form>
  </div>
</script>

<script type="text/template" id="resourceTpEditModalTemplate">
  <div class="resourceTp" data-resource-id="<%- resource.id %>">
    <div class="image"><img class="img-responsive" src="/cgmlImg/<%- resource.image %>/travelPlannerListBig/<%- resource.image %>.jpg"></div>
    <div class="title"><%- resource.title %></div>

    <form>
      <div class="labelText">{t}How long?{/t}</div>
      <div class="hoursContainer row clearfix">
        <div class="col-xs-6">
          <label for="hlong-hour">{t}hours{/t}</label>
          <input type="number" name="hlong-hour" class="hlong-hour" placeholder="{t}hours{/t}" min="0" max="23" value="<%- data.hours %>">
        </div>
        <div class="col-xs-6">
          <label for="hlong-minutes">{t}minutes{/t}</label>
          <input type="number" name="hlong-minutes" class="hlong-minutes" placeholder="{t}minutes{/t}" min="0" max="59" value="<%- data.minutes %>">
        </div>
      </div>
      <div class="buttonActions">
        <button type="button" class="cancelEdit btn btn-warning">{t}Cancel{/t}</button>
        <button type="button" class="acceptEdit btn btn-success">{t}Save{/t}</button>
      <div>
    </form>
  </div>
</script>

<script type="text/template" id="getDatesTpModalTemplate">
  <div class="title">{t}When do you want to travel? {/t}</div>
  <div class="getdatesTpContainer clearfix">
    <div class="icon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
    <input type="text" id="getDatesTpInput" class="form-control" readonly>
  </div>
</script>

<script type="text/template" id="optimizeDayTpModalTemplate">

    <div class="title">{t}Optimize route of day {/t}&nbsp;<%- data.day %></div>
    <p>{t}The route between the first and the last place of the day will be optimized.{/t}<p>
    <div class="row">
      <div class="col-xs-5">
        <div class="routeInit">
          <div class="image"><img class="img-responsive" src="/cgmlImg/<%- data.init.image %>/travelPlannerListBig/<%- data.init.image %>.jpg"></div>
          <div class="title"><%- data.init.title %></div>
        </div>
      </div>
      <div class="col-xs-2">
        <div class="icons">
          <i class="fa fa-caret-right"></i>&nbsp;<i class="fa fa-caret-right"></i>&nbsp;<i class="fa fa-caret-right"></i>&nbsp;<i class="fa fa-caret-right"></i>&nbsp;<i class="fa fa-caret-right"></i>
        </div>
      </div>
      <div class="col-xs-5">
        <div class="routeEnd">
          <div class="image"><img class="img-responsive" src="/cgmlImg/<%- data.end.image %>/travelPlannerListBig/<%- data.end.image %>.jpg"></div>
          <div class="title"><%- data.end.title %></div>
        </div>
      </div>
    </div>
    <div class="buttonActions">
      <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">{t}Cancel{/t}</button>
      <button type="button" class="optimizeRoute btn btn-success">{t}Optimize{/t}</button>
    <div>

</script>
