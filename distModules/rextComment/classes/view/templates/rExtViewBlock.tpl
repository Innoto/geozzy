<!-- rExtViewBlock.tpl en rExtComment module -->
{$client_includes}
{if !isset($commentEmpty)}
<div class="rExtCommentBar clearfix">
  <h4>{t}Comentarios{/t}</h4>

  <div class="averageRate">
    {if isset($resAverageVotes)}
      {assign var="starAverageVotes" value=((($resAverageVotes/10)|round:0)/2)}
      {assign var="starFullAverageVotes" value=$starAverageVotes|string_format:"%d"}
      <div class="star">
        {for $foo=1 to $starFullAverageVotes}
          <i class="fa fa-star" aria-hidden="true"></i>
        {/for}
        {if $starAverageVotes > $starFullAverageVotes}
          <i class="fa fa-star-half-o" aria-hidden="true"></i>
        {/if}
        {for $foo=($starAverageVotes|round:0)+1 to 5}
          <i class="fa fa-star-o" aria-hidden="true"></i>
        {/for}
      </div>
    {/if}
    {if isset($resNumberVotes)}
      <div class="number">({$resNumberVotes})</div>
    {/if}
  </div>

  <div class="commentButtons">
    {if isset($commentButton)}
      <button class="btn btn-primary" onclick="geozzy.commentInstance.createComment({$resID}, 'comment');">{t}Post a comment{/t}</button>
    {/if}
  </div>
</div>
<div class="rExtCommentList">

</div>
{/if}
<!-- /rExtViewBlock.tpl en rExtComment module -->
