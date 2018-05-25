{extends file="admin///adminPanel.tpl"}

{block name="content"}

<script type="text/javascript">
  var geozzy = geozzy || {};
  geozzy.rTypeLikesAdminData = {$likesData|@json_encode}
</script>
<pre>
{var_dump($likesData)}
</pre>

{if isset($blockContent)}
  {$blockContent}
{/if}

{/block}{*/content*}
