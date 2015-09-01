<!-- rExtViewBlock.tpl en rExtFile module -->

<p> --- rExtViewBlock.tpl en rExtFile module</p>

<div class="rExtFile">

  {if $rExtFile_file}
    <div class="fileName">
      <label>{t}File name{/t}</label>
      {$rExtFile_file['name']}
    </div>
    <img src="/cgmlformfilews/{$rExtFile_file[ 'id' ]}"></img>
  {else}
    <p>None</p>
  {/if}



</div>

<!-- /rExtViewBlock.tpl en rExtFile module -->


