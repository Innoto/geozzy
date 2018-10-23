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
</div>
