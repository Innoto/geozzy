var geozzy = geozzy || {};

if(typeof(geozzy.userSessionInstance)=='undefined'){
  geozzy.userSessionInstance = new geozzy.userSession();
  geozzy.userSessionInstance.getUserSession();
}
/*
geozzy.userSessionInstance.userControlAccess( function(){
  alert('access');
});
*/
