<h4>{t}Recursos recomendados:{/t}</h4>

<div class="rExtReccommendedList owl-carousel">

</div>


<script type="text/template" id="recommendedListTemplate">

    <div class="item">
      <div class="itemImage">

          <img class="img-responsive" alt="<%- title %>" src="/cgmlImg/<%- image %>/fast_cut/<%- image %>.jpg" data-description="<%- title %>">
          <div class="trama">
              <div class="destResourceMoreInfo">
                <p><%- shortDescription %></p>
                <a class="btn btn-primary" target="blank" href="/resource/<%- id %>">{t}Find out!{/t}</a>
              </div>
          </div>

      </div>
      <div class="itemTitle">
        <a target="_blank" href="/resource/<%- id %>">
          <h3><%- title %></h3>
        </a>
      </div>
    </div>

</script>
