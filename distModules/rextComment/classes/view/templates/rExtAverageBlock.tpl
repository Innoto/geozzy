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
</div>
