{extends file="adminMasterContent.tpl"}


{block name="contentSection"}

<script type="text/javascript">
  resourceViewData = {if !empty($res.data)}{$res.data|@json_encode}{else}false{/if};
</script>

<ul class="clearfix panelIdTags"></ul>
<div class="admin-cols-12">
  <div class="row">
    <div class="col-lg-12">

      {$col12|default:''}

    </div> <!-- end col-lg-8 -->
  </div> <!-- end row -->
</div>

{/block}{*/contentSection*}
