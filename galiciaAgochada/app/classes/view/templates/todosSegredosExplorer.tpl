{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?language={$GLOBAL_LANG_AVAILABLE[$GLOBAL_C_LANG].i18n}"></script>
{/block}

{block name="bodyContent"}
  <div class="titleBar">
    <div class="container">
      <img class="iconTitleBar img-responsive" alt="{t}Todos os segredos{/t}" src="/media/img/xantaresIcon.png"></img>
      <h1>{t}Todos os segredos{/t}</h1>
    </div>
  </div>
  <div class="todosSegredosExplorer">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}


{block name="footerContent"}

{/block}
