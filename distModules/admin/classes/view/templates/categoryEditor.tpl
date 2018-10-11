


<script type="text/template" id="taxTermEditor">

  <div class="headSection clearfix">
    <div class="row">
      <div class="col-lg-6 col-md-12">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars"></i>
        </button>
        <div class="headerTitleContainer">
          <h3>{t}Category management for{/t} <%- name_{$cogumelo.publicConf.langDefault} %> </h3>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 clearfix">
        <div class="headerActionsContainer">
          <button type="button" class="newTaxTerm btn btn-default"> {t}Add{/t} <%- name %></button>
          <span class="saveChanges">
            <button class="btn btn-danger cancelTerms">{t}Cancel{/t}</button>
            <button class="btn btn-primary saveTerms">{t}Save{/t}</button>
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

                {t}List of terms for{/t} ( <%- name_{$cogumelo.publicConf.langDefault} %> )

            </div>
            <div class="card-body">
              <div id="taxTermListContainer" class="gzznestable <% if (!sortable) { %> no-sortable <% } %> dd">
                <ol class="listTerms dd-list">
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
      <button type="button" class="newTaxTerm btn btn-default">{t}Add{/t} <%- name_{$cogumelo.publicConf.langDefault} %></button>
      <span class="saveChanges">
        <button class="btn btn-danger cancelTerms">{t}Cancel{/t}</button>
        <button class="btn btn-primary saveTerms">{t}Save{/t}</button>
      </span>
    </div>
  </div><!-- /footerSection -->

</script>


<script type="text/template" id="taxTermEditorItem">

  	<li class="dd-item " data-id="<%- term.id %>">
      <div class="dd-item-container clearfix">

        <div class="dd-content">
          <div class="nestableActions">
  	        <button class="btnEditTerm btn-icon btn-info" data-id="<%- term.id %>" ><i class="fa fa-pencil"></i></button>
  	        <button class="btnDeleteTerm btn-icon btn-danger" data-id="<%- term.id %>" ><i class="fa fa-trash"></i></button>
  	      </div>
    	  </div>

        <div class="dd-handle">
          <i class="fa fa-arrows icon-handle"></i>
          <%- term.name_{$cogumelo.publicConf.langDefault} %>
          <% if (term.icon){  %>
            <img class="term-icon img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/<%- term.icon %>-a<%- term.iconAKey %>/fast/">
          <% } %>
        </div>

      </div>
    </li>

</script>
