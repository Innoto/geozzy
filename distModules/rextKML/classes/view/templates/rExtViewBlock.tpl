<!-- rExtViewBlock.tpl en rextKML module -->
{$rExt.data.file|@print_r}
<script>

  {if isset( $rExt.data.file )}

    var rextKMLFile = '{$cogumelo.publicConf.site_host}{$cogumelo.publicConf.mediaHost}cgmlformfilewd/{$rExt.data.file.id}/{$rExt.data.file.name}';
  {else}
    var rextKMLFile = false;
  {/if}

</script>

<!-- /rExtViewBlock.tpl en rextKML module -->
