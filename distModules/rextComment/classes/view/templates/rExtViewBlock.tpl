<!-- rExtViewBlock.tpl en rExtComment module -->
{$client_includes}

<div class="rExtCommentBar clearfix">
  <h4>{t}Comentarios{/t}</h4>


  {if isset($commentButton)}
    <button class="btn btn-primary" onclick="geozzy.commentInstance.createComment({$resID}, 'comment');">{t}Post a comment{/t}</button>
  {/if}
</div>
<div class="rExtCommentList">

</div>

<!-- /rExtViewBlock.tpl en rExtComment module -->
