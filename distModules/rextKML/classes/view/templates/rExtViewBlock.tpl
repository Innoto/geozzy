<!-- rExtViewBlock.tpl en rextKML module -->

<script>

  {if isset( $rExt.data.file )}

    var rextKMLFile = '{$cogumelo.publicConf.site_host}{$cogumelo.publicConf.mediaHost}cgmlformfilewd/{$rExt.data.file.id}/{$rExt.data.file.originalName}';
  {else}
    var rextKMLFile = false;
  {/if}

</script>

<!-- /rExtViewBlock.tpl en rextKML module -->
