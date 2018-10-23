{extends file="adminPanel.tpl"}

{block name="content"}
  <div class="adminAccessDeniedContainer">
      <h3><i class="fas fa-lock"></i> 403 - {t}Forbidden: Access is denied{/t}.</h3>
      <p>{t}You don't have permission to access on this section{/t}.</p>
  </div>
{/block}
