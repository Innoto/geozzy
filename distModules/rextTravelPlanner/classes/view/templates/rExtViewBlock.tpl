<script type="text/template" id="travelPlannerInterfaceTemplate">
  <div class="travelPlanner">
    <div class="travelPlannerBar clearfix">
      <div class="travelPlannerDateButtons clearfix">
        <div class="travelPlannerDateBar"></div>
        <div class="travelPlannerButtonsBar clearfix">
          <div class="tp-gotoPlan">{t}Go to my plan{/t} <i class="fas fa-angle-right" aria-hidden="true"></i></div>
          <div class="tp-goAddtoPlan"><i class="fas fa-angle-left" aria-hidden="true"></i> {t}Add Places{/t}</div>
        </div>
        <div class="travelPlannerHelp">?</div>
      </div>

      <div class="travelPlannerFilterBar clearfix">
        <div class="mode mode1">
          <span>{t}Filtros :{/t}</span>
          <button class="filterByFavourites active"><i class="fas fa-star"></i>&nbsp;{t}Sólo favoritos{/t}</button>
          <!--<select class="filterByFavourites">
            <option value="*">{t}All{/t}</option>
            <option selected="selected" value="fav">{t}Favourites{/t}</option>
          </select>-->
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
        <div class="filterDay-previous"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
        <div class="filterDay-current">{t}Day{/t}&nbsp;<span class="number">1</span></div>
        <div class="filterDay-next"><i class="fas fa-angle-right" aria-hidden="true"></i></div>
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
        <div class="filterDay-previous"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
        <div class="filterDay-current">{t}Day{/t}&nbsp;<span class="number">1</span></div>
        <div class="filterDay-next"><i class="fas fa-angle-right" aria-hidden="true"></i></div>
      </div>
    </div>

      <div class="tp-gotoMobilePlan"><i class="fas fa-list" aria-hidden="true"></i> {t}Plan{/t}</div>
      <div class="tp-gotoMobileList"><i class="fas fa-plus" aria-hidden="true"></i> {t}Add{/t}</div>
      <div class="tp-gotoMobileChangeDates"><i class="far fa-calendar-alt" aria-hidden="true"></i> {t}Dates{/t}</div>
      <!--<div class="tp-gotoMobileMap"><i class="far fa-map" aria-hidden="true"></i> {t}Map{/t}</div>-->

  </div>
</script>

<script type="text/template" id="resourceItemTPTemplate">
  <div class="tpResourceItem" data-resource-id="<%- resource.id %>">
    <div class="image">
      <img class="img-fluid" src="/cgmlImg/<%- resource.image %>/travelPlannerList/<%- resource.image %>.jpg">
      <button class="addToPlan btn btn-primary"><i class="far fa-calendar-plus" aria-hidden="true"></i></button>
      <a href="#resource/<%- resource.id %>">
        <button class="openResource btn btn-default"><i class="fas fa-search" aria-hidden="true"></i></i></button>
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
    <div class="icon"><i class="far fa-calendar-alt" aria-hidden="true"></i></div>
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
         <div class="infoTimeTransport"></div> 
	 <div class="infoTime"><i class="far fa-clock" aria-hidden="true"></i> <span>-h --min</span></div>
        </div>
        <div class="showMap"><i class="far fa-map" aria-hidden="true"></i>&nbsp;<i class="fas fa-angle-double-right" aria-hidden="true"></i></div>
        <div class="optimizeDay"><i class="fas fa-magic " aria-hidden="true"></i>&nbsp;<span>{t}Optimize day{/t}</span></div>
        <div class="printDay hidden-xs hidden-sm"><i class="fas fa-print " aria-hidden="true"></i></div>
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
        <div class="iconHandle"><i class="fas fa-bars icon-handle"></i></div>
        <div class="image"><img class="resImageIcon" src="/cgmlImg/<%- resource.image %>/travelPlannerListIcon/<%- resource.image %>.jpg"></div>
      </div>
      <div class="dd-content">
        <div class="info clearfix">
          <div class="title"><%- resource.title %></div>
          <div class="time"><i class="far fa-clock" aria-hidden="true"></i> <%- resource.timeFormated %></div>
          <div class="infoTimeRoute"></div>
        </div>
        <div class="nestableActions">
          <button class="btnEdit btn-icon btn-primary"><i class="fas fa-pencil-alt" aria-hidden="true"></i></button>
          <button class="btnDelete btn-icon btn-danger"><i class="fas fa-times" aria-hidden="true"></i></button>
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
        <div class="iconHandle"><i class="fas fa-bars icon-handle"></i></div>
        <div class="image"><img class="resImageIcon" src="/cgmlImg/<%- resource.image %>/travelPlannerListIconMobile/<%- resource.image %>.jpg"></div>
      </div>
      <div class="dd-content">
        <div class="info clearfix">
          <div class="title"><%- resource.title %></div>
          <div class="time"><i class="far fa-clock" aria-hidden="true"></i> <%- resource.timeFormated %></div>
          <div class="infoTimeRoute"></div>
        </div>
        <div class="nestableActions">
          <button class="btnEdit btn-icon btn-primary"><i class="fas fa-pencil-alt" aria-hidden="true"></i></button>
          <button class="btnDelete btn-icon btn-danger"><i class="fas fa-times" aria-hidden="true"></i></button>
          <a href="#resource/<%- resource.id %>"><button class="btnAccess btn-icon btn-default"><i class="fas fa-search" aria-hidden="true"></i></button></a>
        </div>
      </div>

    </div>
  </li>
</script>
<!-- *************************** MODAL ITEM ************************************** -->
<script type="text/template" id="resourceTpModalTemplate">
  <div class="resourceTp" data-resource-id="<%- resource.id %>">
    <div class="image"><img class="img-fluid" src="/cgmlImg/<%- resource.image %>/travelPlannerListBig/<%- resource.image %>.jpg"></div>
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
    <div class="image"><img class="img-fluid" src="/cgmlImg/<%- resource.image %>/travelPlannerListBig/<%- resource.image %>.jpg"></div>
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
  <div class="dates">
    <div class="title">{t}When do you want to travel? {/t}</div>
    <div class="getdatesTpContainer clearfix">
      <div class="icon"><i class="far fa-calendar-alt" aria-hidden="true"></i></div>
      <input type="text" id="getDatesTpInput" class="form-control" readonly>
    </div>
  </div>
</script>

<script type="text/template" id="helpTpModalTemplate">
  <div class="howTo">
    <div class="moreInfo">
      <div class="title">{t}Organizing your trip is very easy{/t}</div>
      <p>{t}Is this your first time using our travel planner?{/t}</p>
      <p>{t}Follow these simple steps{/t}</p>
    </div>
    <div class="stepOne steps">
      <p>{t}STEP 1: While exploring the contents of the website,{/t} {t}you can select any content that might interest you and append it to your ‘favourites collection’.{/t} {t}Just click on the star icon.{/t}</p>
      <img src="{$cogumelo.publicConf.media}/img/travelPlanner/step1.png" class="img-fluid center-block" alt="{t}Step 1 - Travel Planner{/t}">
    </div>
    <div class="stepTwo steps">
      <p>{t}STEP 2: Access the travel planner, and select your traveling dates{/t}</p>
      <img src="{$cogumelo.publicConf.media}/img/travelPlanner/step2.png" class="img-fluid center-block" alt="{t}Step 2 - Travel Planner{/t}">
    </div>
    <div class="stepThree steps">
      <p>{t}STEP 3: From your favorite collection, select those places that you would like to add to your journey plan.{/t} {t}You can specify the day that most interests you to visit it and / or the time you want to spend there{/t}</p>
      <img src="{$cogumelo.publicConf.media}/img/travelPlanner/step3.png" class="img-fluid center-block" alt="{t}Step 3 - Travel Planner{/t}">
    </div>
    <div class="stepFour steps">
      <p>{t}STEP 4: Press ‘Go to my plan’.{/t} {t}You can schedule visits as you wish by dragging and dropping the items. {/t}</p>
      <img src="{$cogumelo.publicConf.media}/img/travelPlanner/step4.png" class="img-fluid center-block" alt="{t}Step 4 - Travel Planner{/t}">
    </div>
    <div class="stepFive steps">
      <p>{t}STEP 5: You can also optimize your day of visits. Simply click on 'Optimize day'{/t}</p>
      <img src="{$cogumelo.publicConf.media}/img/travelPlanner/step5.png" class="img-fluid center-block" alt="{t}Step 5 - Travel Planner{/t}">
      <br>
      <br>
      <p>{t}Before optimizing the route, the desired place of departure should be placed as the first element and the desired arrival place as the last one. Both elements could be the same place.{/t}</p>
    </div>
    <div class="stepSix steps">
      <p>{t}STEP 6: Follow your travel plan on your smartphone{/t}</p>
      <img src="{$cogumelo.publicConf.media}/img/travelPlanner/step6.png" class="img-fluid center-block" alt="{t}Step 6 - Travel Planner{/t}">
    </div>
    <div class="end steps">
      <p>{t}Shall we begin?{/t}</p>
      <p>{t}Choose your dates and start your travel plan{/t}</p>
    </div>
  </div>
  <button class="selectYourDates btn btn-success">{t}Select your dates{/t}</button>
</script>

<script type="text/template" id="optimizeDayTpModalTemplate">

    <div class="title">{t}Optimize route of day {/t}&nbsp;<%- data.day %></div>
    <p>{t}The route between the first and the last place of the day will be optimized.{/t}<p>
    <div class="row">
      <div class="col-xs-5">
        <div class="routeInit">
          <div class="image"><img class="img-fluid" src="/cgmlImg/<%- data.init.image %>/travelPlannerListBig/<%- data.init.image %>.jpg"></div>
          <div class="title"><%- data.init.title %></div>
        </div>
      </div>
      <div class="col-xs-2">
        <div class="icons">
          <i class="fas fa-caret-right"></i>&nbsp;<i class="fas fa-caret-right"></i>&nbsp;<i class="fas fa-caret-right"></i>&nbsp;<i class="fas fa-caret-right"></i>&nbsp;<i class="fas fa-caret-right"></i>
        </div>
      </div>
      <div class="col-xs-5">
        <div class="routeEnd">
          <div class="image"><img class="img-fluid" src="/cgmlImg/<%- data.end.image %>/travelPlannerListBig/<%- data.end.image %>.jpg"></div>
          <div class="title"><%- data.end.title %></div>
        </div>
      </div>
    </div>
    <div class="buttonActions">
      <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">{t}Cancel{/t}</button>
      <button type="button" class="optimizeRoute btn btn-success">{t}Optimize{/t}</button>
    <div>

</script>

<script type="text/template" id="printDayTpModalTemplate">
  <h1 class="title day text-center">{t}Day{/t} &nbsp;<%- data.day %></h1>
  <div class="timeTotal clearfix">
    <div class="infoTotalTimeTransport">
      <%= data.stringRouteMode %> <%= data.stringTotalTimeTransport %>
    </div>
    <div class="time infoTotalTime"><i class="far fa-clock" aria-hidden="true"></i>
      <%= data.stringTotalResourceTimes %>
    </div>
  </div>
  <% _.each( data.resourcesTimes, function( i, e ) { %>
    <% _.each( data.resources, function( elem, iteration ) { %>
      <% if (i.id == elem.id) { %>
        <div class="resource clearfix">
          <% _.each( data.route.routes[0].legs, function( j, leg ) { %>
            <% if (e == leg) { %>
              <div class="startAddress center-block">
                <table class="">
                  <tr>
                    <td>
                      <span class="start">{t}Comienzo{/t}: </span>
                        <%= j.start_address %>
                    </td>
                  </tr>
                </table>
              </div>
              <% } %>
            <% }); %>
          <h2 class="title name text-center"><%= elem.title %></h2>
          <div class="resourceData clearfix">
            <div class="infoLeft">
              <div class="imgResource">
                <img class="img-fluid center-block" src="/cgmlImg/<%- elem.image %>/travelPlannerList/<%- elem.image %>.jpg">
              </div>
              <div class="times clearfix">
                <% minutes = i.time % 60 %>
                <% hours = (i.time - minutes) / 60 %>
                <div class="infoTimeTransport"><%= data.routeTimes[e].stringTime %></div>
                <div class="time infoTime">
                  <i class="far fa-clock" aria-hidden="true"></i>
                    <%= hours %> h <%= minutes %> min
                </div>
              </div>
            </div>
            <div class="infoRight">
              <div class="directions clearfix">
                <% _.each( data.route.routes[0].legs, function( j, leg ) { %>
                  <% if (e == leg) { %>
                    <!-- <table class="startAddress">
                      <tr>
                        <td>
                          <span class="start">{t}Comienzo{/t}: </span>
                            <%= j.start_address %>
                        </td>
                      </tr>
                    </table> -->
                    <table class="steps">
                      <% _.each( j.steps, function( k, step ) { %>
                        <tr>
                          <td><%= step+1 %>. </td>
                          <td><%= k.instructions %></td>
                          <td class="distance"><%= k.distance.text %></td>
                          <td class="duration"><%= k.duration.text %></td>
                        </tr>
                      <% }); %>
                    </table>
                    <!-- <table class="endAddress">
                      <tr>
                        <td>
                          <span class="end">{t}Llegada{/t}: </span>
                            <%= j.end_address %>
                        </td>
                      </tr>
                    </table> -->
                  <% } %>
                <% }); %>
              </div>
            </div>
          </div>
          <% _.each( data.route.routes[0].legs, function( j, leg ) { %>
            <% if (e == leg) { %>
              <div class="endAddress center-block">
                <table class="">
                  <tr>
                    <td>
                      <span class="end">{t}Llegada{/t}: </span>
                        <%= j.end_address %>
                    </td>
                  </tr>
                </table>
              </div>
            <% } %>
          <% }); %>
        </div>
      <% } %>
    <% }); %>
  <% }); %>
</script>
