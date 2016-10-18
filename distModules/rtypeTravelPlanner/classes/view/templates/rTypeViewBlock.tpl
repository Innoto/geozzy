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
    PLANIFICADOR!!!!!
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
          <select>
            <option value="1">All</option>
            <option value="2">Favourites</option>
          </select>
        </div>
        <div class="filter">
          <select>
            <option value="">All</option>
            <option value="rtypeHotel">Hotel</option>
            <option value="rtypeRestaurante">Restaurante</option>
          </select>
        </div>
      </div>
      <div class="travelPlannerResources"></div>
    </div>
    <div class="travelPlannerPlan"></div>
  </div>
</script>


<script type="text/template" id="resourceItemTPTemplate">
  <div class="tpResourceItem" data-resource-id="<%- resource.id %>">
    <div class="title"><%- resource.title %></div>
    <div class="description"><%- resource.mediumDescription %></div>
    <div class="image"><img class="img-responsive" src="/cgmlImg/<%- resource.image %>/fast_cut/<%- resource.image %>.jpg"></div>
  </div>
</script>
