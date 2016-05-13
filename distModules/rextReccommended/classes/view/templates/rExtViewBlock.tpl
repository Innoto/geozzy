{t}Recursos recomendados:{/t}
<div class="rExtReccommendedList">

</div>

<script type="text/template" id="recommendedListTemplate">
  <div class="item">
    <div class="itemImage">

        <img class="img-responsive" alt="<%- title %>" src="<%- image %>" data-description="<%- title %>">
        <div class="trama">
            <div class="destResourceMoreInfo">
              <a class="btn btn-primary" href="/<%- urlAlias %>">{t}Find out!{/t}</a>
            </div>
        </div>

    </div>
    <div class="itemTitle">
      <a target="_blank" href="/<%- urlAlias %>">
        <h3><%- title %></h3>
        ID: <%- id %>
      </a>
    </div>
  </div>
</script>
