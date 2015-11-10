<!-- rExtViewBlock.tpl en rExtFile module -->

<p> --- rExtViewBlock.tpl en rExtFile module</p>

<div class="rExtFile">

  {if isset( $rExtFile_file )}
    <div class="fileName">
      <label>{t}File name{/t}</label>
      {$rExtFile_file.name}
    </div>
    {if strpos( $rExtFile_file.type, 'image/' ) === 0 }
      <img src="/cgmlformfilews/{$rExtFile_file.id}"
        {if isset( $rExtFile_file.title )}alt="{$rExtFile_file.title}" title="{$rExtFile_file.title}"{/if}></img>
    {else}
      <a href="/cgmlformfilewd/{$rExtFile_file.id}" target="_blank">{t}Download{/t} {$rExtFile_file.name}</a>
    {/if}

  {else}
    <p>{t}None{/t}</p>
  {/if}



</div>

<!-- /rExtViewBlock.tpl en rExtFile module -->
