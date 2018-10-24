{block name="headCssIncludes" append}

{/block}

<!-- rTypeViewBlock.tpl en rTypeTravelPlanner module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <!--<div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-fluid" alt="{t}Page{/t}" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png"></img>
          <h1>{t}Travel Planner{/t}</h1>
        </div>
      </div>
    </div>
  </div>-->
  <div class="tpDuResource" style=""></div>
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


{$rextTravelPlannerBlock}
