{block name="headCssIncludes" append}
<style type="text/css">
  .TravelPlannerElement { height: 250px; }
  .TravelPlannerElement img { width: 100%; }
  .favDelete {
    position: absolute;
    right: 20px;
    top: 5px;
    display: block;
    padding: 2px;
    /* background-color: yellow; */
    color: #FF2222;
    font-size: 20px;
  }
  .TravelPlannerElement .title {
    text-decoration: underline;
  }
  .TravelPlannerElement .shortDescription {
    font-size: 90%
  }
</style>
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
