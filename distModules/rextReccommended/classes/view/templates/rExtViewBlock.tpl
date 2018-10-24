<h4>{t}Recursos recomendados:{/t}</h4>

<div class="rExtReccommendedList owl-carousel">

</div>


<script type="text/template" id="recommendedListTemplate">

    <div class="item isResource" data-related-resource-id="{$rExt.data.id}">
      <div class="itemImage">

          <img class="img-fluid" alt="<%- title %>" src="/cgmlImg/<%- image %>/fast_cut/<%- image %>.jpg" data-description="<%- title %>">
          <div class="trama">
              <div class="destResourceMoreInfo">
                <p><%- shortDescription %></p>
                <a class="btn btn-primary" target="blank" href="<%- urlAlias %>">{t}Find out!{/t}</a>
              </div>
          </div>

      </div>
      <div class="itemTitle">
        <a target="_blank" href="<%- urlAlias %>">
          <h3><%- title %></h3>
        </a>
      </div>
    </div>

</script>

<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtReccommendedOptions = {
    resId: {$rExt.data.id}
  }
</script>
