var nextPage = 1;
var page = 1;
var limitPage = 1;

$( document ).ready(function(){
 var pageGet = getParameterByName('p');
 if(pageGet){
  page = pageGet;
 }
 limitPage = ($('.secBlogList .paginator').attr('data-limit-page')) ? $('.secBlogList .paginator').attr('data-limit-page'): 1;
 if( limitPage > page ){
   $('.secBlogList .loading').show();
 }
 $('.secBlogList .paginator').hide();
 $(window).scroll(startInfiniteScroll);
});


function startInfiniteScroll(){
  var windowTop = $(document).scrollTop();
  var windowBottom = windowTop + window.innerHeight;
  var elementPositionTop = $('.secBlogList .blogContainer').find('.individualBlog').last().offset().top;

  if( elementPositionTop < windowBottom ){
    loadNextPage();
  }
}
function loadNextPage(){
  if(nextPage){
    nextPage = 0;
    page++;
    if(limitPage >= page){
      var urlParamBlog = (cogumelo.publicConf.C_LANG == 'gl') ? '/'+cogumelo.publicConf.C_LANG+'/blogue?p='+page : '/blog?p='+page ;

      $.ajax({
        url: urlParamBlog,
        dataType: "html",
        context: document.body
      }).done(function(data) {
        if(data){
          newHtmlBlogs = $(data).find('.blogContainer').html();
          $('.secBlogList .blogContainer').append(newHtmlBlogs);
          nextPage = 1;
        }else{
          $('.secBlogList .loading').hide();
        }
      });
    }else{
      $('.secBlogList .loading').hide();
    }
  }
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
