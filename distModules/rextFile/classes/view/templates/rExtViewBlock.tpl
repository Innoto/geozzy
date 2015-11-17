<!-- rExtViewBlock.tpl en rExtFile module -->

<p> --- rExtViewBlock.tpl en rExtFile module</p>

<div class="rExtFile">

  {if isset( $rExt.data.file )}
    <div class="fileName">
      <label>{t}File name{/t}</label>
      {$rExt.data.file.name}
    </div>
    {if strpos( $rExt.data.file.type, 'image/' ) === 0 }
      <img src="/cgmlformfilews/{$rExt.data.file.id}" {if isset( $rExt.data.file.title )}alt="{$rExt.data.file.title}" title="{$rExt.data.file.title}"{/if}></img>
    {else}
      <a href="/cgmlformfilewd/{$rExt.data.file.id}" target="_blank">{t}Download{/t} {$rExt.data.file.name}</a>
    {/if}

  {else}
    <p>{t}None{/t}</p>
  {/if}



</div>

<!-- /rExtViewBlock.tpl en rExtFile module -->
