{block name="headCssIncludes" append}

{/block}

<!-- rTypeViewBlock.tpl en rTypeTravelPlanner module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-responsive" alt="{t}Page{/t}" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png"></img>
          <h1>{t}Travel Planner{/t}</h1>
        </div>
      </div>
    </div>
  </div>

  <section id="travelPlannerSec" class="gzSection">
  </section>

  <script type="text/javascript">
    $(document).ready(function(){
      if(typeof(geozzy.travelPlannerInstance)=='undefined'){
        geozzy.travelPlannerInstance = new geozzy.travelPlanner({$res.data.id});
      }
      geozzy.travelPlannerInstance.init();
    });
  </script>


</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypeTravelPlanner module -->



<script type="text/template" id="travelPlannerInterfaceTemplate">
  <div class="travelPlanner">
    <div class="travelPlannerList">
      <div class="travelPlannerFilters">
        <div class="filter">
          <select class="filterByFavourites">
            <option value="*">{t}All{/t}</option>
            <option value="fav">{t}Favourites{/t}</option>
          </select>
        </div>
        <div class="filter">
          <select class="filterByRtype">
            <option value="*">{t}All{/t}</option>
            <% _.each( rtypesFilters, function( elem ) { %>
              <option value="<%= elem.idName %>"><%= elem.name %></option>
            <% }); %>

          </select>
        </div>
      </div>
      <div class="travelPlannerResources"></div>
    </div>
    <div class="travelPlannerPlan">
      <div class="travelPlannerPlanHeader"></div>
      <div class="travelPlannerPlanDaysContainer"></div>
    </div>
  </div>
</script>

<script type="text/template" id="resourceItemTPTemplate">
  <div class="tpResourceItem" data-resource-id="<%- resource.id %>">
    <div class="image">
      <img class="img-responsive" src="/cgmlImg/<%- resource.image %>/travelPlannerList/<%- resource.image %>.jpg">
      <button class="addToPlan btn btn-primary"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i></button>
      <button class="openResource btn btn-default"><i class="fa fa-search" aria-hidden="true"></i></i></button>
    </div>
    <div class="title"><%- resource.title %></div>
    <div class="description"><%- resource.shortDescription %></div>

  </div>
</script>

<script type="text/template" id="datesTPTemplate">
  <div class="datesTpContainer">
    <div class="title">{t}When you want to make your visit?{/t}</div>
    <label for="checkTpDates">{t}Check in date - Check out date{/t}</label>
    <input type="text" id="checkTpDates" class="form-control" readonly>
  </div>
</script>

<script type="text/template" id="dayTPTemplate">

  <div class="plannerDay plannerDay-<%- day.id %>" data-day="<%- day.id %>">
    <h2><%- day.dayName %> <%- day.day %> <%- day.month %></h2>
    <div class="plannerDayPlanner gzznestable dd">
      <ol class="dd-list">

      </ol>
      <!--<div class="dd-empty"></div>-->
    </div>
  </div>

</script>

<script type="text/template" id="resourceTpModalTemplate">
  <div class="resourceTp" data-resource-id="<%- resource.id %>">
    <div class="image"><img class="img-responsive" src="/cgmlImg/<%- resource.image %>/fast_cut/<%- resource.image %>.jpg"></div>
    <div class="title"><%- resource.title %></div>
    <ul class="selectorDays clearfix">
      <% _.each(dates, function(i) { %>
        <li class="day-<%= i.id %> <% if(i.inPlan){ %> inPlan <% } %>" data-day="<%= i.id %>">
          <div class="dayName"><%= i.dayName %></div>
          <div class="dayNumber"><%= i.day %></div>
          <div class="month"><%= i.month %></div>
        </li>
      <% }); %>
    </ul>

    <div class="buttonActions">
      <button type="button" class="cancelAdd btn btn-warning">{t}Cancelar{/t}</button>
      <button type="button" class="acceptAdd btn btn-success">{t}Add to plan{/t}</button>
    <div>
  </div>
</script>

<script type="text/template" id="resourcePlanItemTemplate">
  <li class="dd-item" data-id="<%- resource.id %>">
    <div class="dd-item-container clearfix">
      <div class="dd-content">
        <div class="nestableActions">
          <button class="btnDelete btn-icon btn-danger" data-id="<%- resource.id %>" ><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="dd-handle">
        <i class="fa fa-arrows icon-handle"></i>
        <img class="resImageIcon" src="/cgmlImg/<%- resource.image %>/travelPlannerListIcon/<%- resource.image %>.jpg">
        <%- resource.title %>
      </div>
    </div>
  </li>
</script>
