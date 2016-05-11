{extends file="admin///adminPanel.tpl"}

{block name="content"}

<h3>FAVORITOS TPL</h3>

<pre>
{var_dump($favData)}
</pre>

{if isset($blockContent)}
  {$blockContent}
{/if}

{/block}{*/content*}
