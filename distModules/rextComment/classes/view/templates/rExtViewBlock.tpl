<!-- rExtViewBlock.tpl en rExtComment module -->
{if !isset($commentEmpty)}
<div class="rExtCommentBar clearfix">
  <h4 class="commentTitle">{t}Comentarios{/t}</h4>

  <div class="averageRate">
    {if isset($resAverageVotes)}
      {assign var="starAverageVotes" value=((($resAverageVotes/10)|round:0)/2)}
      {assign var="starFullAverageVotes" value=$starAverageVotes|string_format:"%d"}
      <div class="star">
        {for $foo=1 to $starFullAverageVotes}
          <i class="fas fa-star" aria-hidden="true"></i>
        {/for}
        {if $starAverageVotes > $starFullAverageVotes}
          <i class="fas fa-star-half-alt" aria-hidden="true"></i>
        {/if}
        {for $foo=($starAverageVotes|round:0)+1 to 5}
          <i class="far fa-star" aria-hidden="true"></i>
        {/for}
      </div>
    {/if}
    {if isset($resNumberVotes)}
      <div class="number"><span>(</span>{$resNumberVotes}<span>)</span></div>
    {/if}
  </div>

  <div class="commentButtons">
    {if isset($suggestButton) && isset($commentButton)}
      {if isset($commentButton)}
        {if empty($anonymousPerms)}
          <button class="btn btn-primary" onclick="geozzy.userSessionInstance.userControlAccess( function(){
            geozzy.commentInstance.createComment({$resID});
          });"><i class="fas fa-plus" aria-hidden="true"></i>{t}Post a comment or suggestion{/t}</button>
        {else}
          <button class="btn btn-primary" onclick="geozzy.commentInstance.createComment({$resID});"><i class="fas fa-plus" aria-hidden="true"></i>{t}Post a comment or suggestion{/t}</button>
        {/if}
      {/if}

    {else}
      {if empty($anonymousPerms)}
        {if isset($commentButton)}
          <button class="btn btn-primary" onclick="geozzy.userSessionInstance.userControlAccess( function(){ geozzy.commentInstance.createComment({$resID}, 'comment'); });"><i class="fas fa-plus" aria-hidden="true"></i>{t}Post a comment{/t}</button>
        {/if}
        {if isset($suggestButton)}
          <button class="btn btn-primary" onclick="geozzy.userSessionInstance.userControlAccess( function(){ geozzy.commentInstance.createComment({$resID}, 'suggest'); });"><i class="fas fa-plus" aria-hidden="true"></i>{t}Post a suggestion{/t}</button>
        {/if}
      {else}
        {if isset($commentButton)}
          <button class="btn btn-primary" onclick="geozzy.commentInstance.createComment({$resID}, 'comment');"><i class="fas fa-plus" aria-hidden="true"></i>{t}Post a comment{/t}</button>
        {/if}
        {if isset($suggestButton)}
          <button class="btn btn-primary" onclick="geozzy.commentInstance.createComment({$resID}, 'suggest');"><i class="fas fa-plus" aria-hidden="true"></i>{t}Post a suggestion{/t}</button>
        {/if}
      {/if}

    {/if}
  </div>
</div>
<div class="rExtCommentList">
  <div class="loading"><i class="fas fa-circle-notch fa-spin fa-3x fa-fw"></i><div>{t}Loading...{/t}</div></div>
</div>

{else}

<div class="rExtCommentBar clearfix">
  <div class="commentButtons">
    {if isset($suggestButton)}
      <button class="btn btn-primary" onclick="geozzy.commentInstance.createComment({$resID}, 'suggest');"><i class="fas fa-plus" aria-hidden="true"></i>{t}Post a suggestion{/t}</button>
    {/if}
  </div>
</div>

{/if}
<!-- /rExtViewBlock.tpl en rExtComment module -->
