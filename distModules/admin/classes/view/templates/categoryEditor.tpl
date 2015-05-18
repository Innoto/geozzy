


<script type="text/template" id="taxTermEditor">

  <div class="headSection clearfix">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="headerTitleContainer">
          <h2>Listado de terminos para  <%- name %> </h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="headerActionsContainer">
          <button type="button" class="newTaxTerm btn btn-default"> Añadir a <%- name %></button>
        </div>
      </div>
    </div>
    <!-- /.navbar-header -->
  </div><!-- /headSection -->


  <div class="contentSection clearfix">
    <div class="admin-cols-8-4">
      <div class="row">
        <div class="col-lg-8">
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>
                <i class="fa fa-tag fa-fw"></i>
                Listado de terminos para ( <%- name %> )
              </strong>
            </div>
            <div class="panel-body">
              <div id="taxTermListContainer" class="dd">
                <ol class="listTerms dd-list">
                </ol>
              </div>
              <div class="saveChanges">
                <button class="btn btn-danger cancelTerms">Cancel</button>
                <button class="btn btn-primary saveTerms">Save</button>
              </div>
            </div> <!-- end panel-body -->
          </div> <!-- end panel -->
        </div> <!-- end col -->
      </div> <!-- end row -->
    </div>

  </div><!-- /contentSection -->


  <div class="footerSection clearfix">
    <div class="headerActionsContainer">
      <button type="button" class="newTaxTerm btn btn-default">Añadir a <%- name %></button>
    </div>
  </div><!-- /footerSection -->

</script>


<script type="text/template" id="taxTermEditorItem">

  	<li class="dd-item" data-id="<%- term.id %>">
      <div class="dd-item-container clearfix">

        <div class="dd-content">
          <div class="taxTermActions">
  	        <button class="btnEditTerm btn btn-default btn-info" data-id="<%- term.id %>" ><i class="fa fa-pencil"></i></button>
  	        <button class="btnDeleteTerm btn btn-default btn-danger" data-id="<%- term.id %>" ><i class="fa fa-trash"></i></button>
  	      </div>
    	  </div>

        <div class="dd-handle">
          <i class="fa fa-arrows icon-handle"></i>
          <%- term.name_{$langDefault} %>
        </div>

      </div>
    </li>

</script>
