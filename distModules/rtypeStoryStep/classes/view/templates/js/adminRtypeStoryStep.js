$(document).ready(function(){

  $('.RTypeStoryStepView .tableHeaderContainer .tableActions').hide();

  /* Bindeamos unha acción da táboa ao botón da barra superior*/
  $('.btnAssign').bind('click', function(){
    cogumeloTables.RTypeStoryStepView.actionOnSelectedRows('assign', function() {
      window.location = 'admin#storysteps/'+parts2[2];
    });
  });
});
