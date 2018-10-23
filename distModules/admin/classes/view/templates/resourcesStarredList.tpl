


<script type="text/template" id="resourcesStarredList">

  <div class="headSection clearfix">
    <div class="row">
      <div class="col-12 col-lg-6 clearfix">
        <button type="button" class="navbar-gzz-toggle" >
            <span class="sr-only">Toggle navigation</span>
            <i class="fas fa-bars"></i>
        </button>
        <div class="headerTitleContainer">
          <h3>{t}Resources Starred in{/t} <%- name_{$cogumelo.publicConf.langDefault} %> </h3>
        </div>
      </div>
      <div class="col-12 col-lg-6 clearfix">
        <div class="headerActionsContainer">
          <button type="button" class="assignResourceTerm btn btn-default"> {t}Assign{/t}</button>
          <span class="saveChanges">
            <button class="btn btn-danger cancel">{t}Cancel{/t}</button>
            <button class="btn btn-primary save">{t}Save{/t}</button>
          </span>
        </div>
      </div>
    </div>
    <!-- /.navbar-header -->
  </div><!-- /headSection -->


  <div class="contentSection clearfix">
    <div class="admin-cols-8-4">
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">

                {t}List of resources for{/t} ( <%- name_{$cogumelo.publicConf.langDefault} %> )

            </div>
            <div class="card-body">
              <div id="resourcesStarredListContainer" class="gzznestable dd">
                <ol class="listResources dd-list">
                </ol>
              </div>
            </div> <!-- end card-body -->
          </div> <!-- end card -->
        </div> <!-- end col -->
      </div> <!-- end row -->
    </div>

  </div><!-- /contentSection -->


  <div class="footerSection clearfix">
    <div class="headerActionsContainer">
      <button type="button" class="assignResourceTerm btn btn-default"> {t}Assign{/t}</button>
      <span class="saveChanges">
        <button class="btn btn-danger cancel">{t}Cancel{/t}</button>
        <button class="btn btn-primary save">{t}Save{/t}</button>
      </span>
    </div>
  </div><!-- /footerSection -->

</script>


<script type="text/template" id="resourcesStarredItem">

  	<li class="dd-item" data-id="<%- resource.id %>">
      <div class="dd-item-container clearfix">

        <div class="dd-content">
          <div class="nestableActions">
  	        <button class="btnDelete btn-icon btn-danger" data-id="<%- resource.id %>" ><i class="fas fa-trash-alt fa-sm fa-fw"></i></button>
  	      </div>
    	  </div>

        <div class="dd-handle">
          <i class="fas fa-arrows-alt fa-fw icon-handle"></i>
          <%- resource.title_{$cogumelo.publicConf.langDefault} %>
        </div>

      </div>
    </li>

</script>
