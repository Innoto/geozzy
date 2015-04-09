
<script type="text/template" id="taxTermEditor">
   
</script>


<script type="text/template" id="taxTermEditorItem">

	<li class="list-group-item" termId="<% id %>">
	  <div class="row">
	    <div class="col-md-9">
	      <div class="infoTerm"><% name %></div>
	      <div class="editTermContainer">
	          <input type="text" class="editTermInput" value="<% name %>" />
	      </div>
	    </div>
	    <div class="col-md-3">
	      <button class="btnSaveTerm btn btn-default btn-success"><i class="fa fa-check"></i></button>
	      <button class="btnEditTerm btn btn-default btn-info"><i class="fa fa-pencil"></i></button>
	      <button class="btnDeleteTerm btn btn-default btn-danger"><i class="fa fa-trash"></i></button>
	      <button class="btnCancelTerm btn btn-default btn-danger"><i class="fa fa-close"></i></button>
	    </div>
	  </div>
	</li>

</script>
