var geozzy = geozzy || {};


geozzy.travelPlanner = function( idTravelPlanner ) {

  var that = this;

  that.travelPlannerId = (idTravelPlanner) ? idTravelPlanner : false;
  that.travelPlannerInterfaceView = false;
  var resParam = {
    fields: false,
    filters: false,
    rtype: false,
    urlAlias: true
  }
  if(cogumelo.publicConf.mod_geozzy_travelPlanner.toString() !== ''){
    resParam.rtype = cogumelo.publicConf.mod_geozzy_travelPlanner.toString();
  }

  that.resources = new geozzy.collection.ResourceCollection( resParam );
  that.favInfo = false;
  that.favResources = false;




  that.getResourcesFav = function(){
    var formData = new FormData();
    formData.append( 'cmd', 'listFavs' );

    var url = '/api/favourites';

    if( typeof cogumelo.publicConf.C_LANG === 'string' ) {
      url = '/'+cogumelo.publicConf.C_LANG+url;
    }

    $.ajax({
      url: url, type: 'POST',
      data: formData, cache: false,
      contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          that.favInfo = $jsonData.favourites;
          that.favResources = $jsonData.favourites[Object.keys($jsonData.favourites)[0]].resourceList;
        }
      }
    });
  }
  // First Execution
  that.init = function( ) {
    console.log('travelPlannerID:'+ that.travelPlannerId );
    that.getResourcesFav();

    $.when( that.resources.fetch() ).done(function() {
      that.travelPlannerInterfaceView = new geozzy.travelPlannerComponents.TravelPlannerInterfaceView(that);
    });
  }
}

/*


var htmlTravelPlanner = '';

htmlTravelPlanner += '<div class="plannerContainer">';
  htmlTravelPlanner += '<div class="plannerList">';

  htmlTravelPlanner += '</div>';
  htmlTravelPlanner += '<div class="planner">';
    htmlTravelPlanner += '<div class="row">';
      htmlTravelPlanner += '<div class="col-md-4">';
        htmlTravelPlanner += '<div class="plannerDay">';
          htmlTravelPlanner += '<h2>DAY 1</h2>';
          htmlTravelPlanner += '<div class="plannerDayPlanner gzznestable dd">';

            htmlTravelPlanner += '<ol class="dd-list">';
              ///////////////////////////////////////////////////////////////
              htmlTravelPlanner += '<li class="dd-item" data-id="10">';
                htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                  htmlTravelPlanner += '<div class="dd-content">';
                    htmlTravelPlanner += '<div class="nestableActions">';
                      htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="10" ><i class="fa fa-trash"></i></button>';
                    htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '<div class="dd-handle">';
                    htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                    htmlTravelPlanner += 'ITEM 10';
                  htmlTravelPlanner += '</div>';
                htmlTravelPlanner += '</div>';
              htmlTravelPlanner += '</li>';
              ///////////////////////////////////////////////////////////////
              htmlTravelPlanner += '<li class="dd-item" data-id="11">';
                htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                  htmlTravelPlanner += '<div class="dd-content">';
                    htmlTravelPlanner += '<div class="nestableActions">';
                      htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="11" ><i class="fa fa-trash"></i></button>';
                    htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '<div class="dd-handle">';
                    htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                    htmlTravelPlanner += 'ITEM 11';
                  htmlTravelPlanner += '</div>';
                htmlTravelPlanner += '</div>';
              htmlTravelPlanner += '</li>';
              ///////////////////////////////////////////////////////////////
              htmlTravelPlanner += '<li class="dd-item" data-id="11">';
                htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                  htmlTravelPlanner += '<div class="dd-content">';
                    htmlTravelPlanner += '<div class="nestableActions">';
                      htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="11" ><i class="fa fa-trash"></i></button>';
                    htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '<div class="dd-handle">';
                    htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                    htmlTravelPlanner += 'ITEM 11-2';
                  htmlTravelPlanner += '</div>';
                htmlTravelPlanner += '</div>';
              htmlTravelPlanner += '</li>';
              ///////////////////////////////////////////////////////////////
              htmlTravelPlanner += '<li class="dd-item" data-id="13">';
                htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                  htmlTravelPlanner += '<div class="dd-content">';
                    htmlTravelPlanner += '<div class="nestableActions">';
                      htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="13" ><i class="fa fa-trash"></i></button>';
                    htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '<div class="dd-handle">';
                    htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                    htmlTravelPlanner += 'ITEM 13';
                  htmlTravelPlanner += '</div>';
                htmlTravelPlanner += '</div>';
              htmlTravelPlanner += '</li>';
              ///////////////////////////////////////////////////////////////
              htmlTravelPlanner += '<li class="dd-item" data-id="14">';
                htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                  htmlTravelPlanner += '<div class="dd-content">';
                    htmlTravelPlanner += '<div class="nestableActions">';
                      htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="14" ><i class="fa fa-trash"></i></button>';
                    htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '<div class="dd-handle">';
                    htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                    htmlTravelPlanner += 'ITEM 14';
                  htmlTravelPlanner += '</div>';
                htmlTravelPlanner += '</div>';
              htmlTravelPlanner += '</li>';
              ///////////////////////////////////////////////////////////////
            htmlTravelPlanner += '</ol>';

          htmlTravelPlanner += '</div>';
        htmlTravelPlanner += '</div>';
      htmlTravelPlanner += '</div>';
      htmlTravelPlanner += '<div class="col-md-4">';

        htmlTravelPlanner += '<div class="plannerDay">';
          htmlTravelPlanner += '<h2>DAY 2</h2>';
          htmlTravelPlanner += '<div class="plannerDayPlanner gzznestable dd">';

            htmlTravelPlanner += '<ol class="dd-list">';
              ///////////////////////////////////////////////////////////////
              htmlTravelPlanner += '<li class="dd-item" data-id="15">';
                htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                  htmlTravelPlanner += '<div class="dd-content">';
                    htmlTravelPlanner += '<div class="nestableActions">';
                      htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="15" ><i class="fa fa-trash"></i></button>';
                    htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '<div class="dd-handle">';
                    htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                    htmlTravelPlanner += 'ITEM 15';
                  htmlTravelPlanner += '</div>';
                htmlTravelPlanner += '</div>';
              htmlTravelPlanner += '</li>';
              ///////////////////////////////////////////////////////////////
              htmlTravelPlanner += '<li class="dd-item" data-id="16">';
                htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                  htmlTravelPlanner += '<div class="dd-content">';
                    htmlTravelPlanner += '<div class="nestableActions">';
                      htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="16" ><i class="fa fa-trash"></i></button>';
                    htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '<div class="dd-handle">';
                    htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                    htmlTravelPlanner += 'ITEM 16';
                  htmlTravelPlanner += '</div>';
                htmlTravelPlanner += '</div>';
              htmlTravelPlanner += '</li>';
              ///////////////////////////////////////////////////////////////
              htmlTravelPlanner += '<li class="dd-item" data-id="17">';
                htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                  htmlTravelPlanner += '<div class="dd-content">';
                    htmlTravelPlanner += '<div class="nestableActions">';
                      htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="17" ><i class="fa fa-trash"></i></button>';
                    htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '<div class="dd-handle">';
                    htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                    htmlTravelPlanner += 'ITEM 17';
                  htmlTravelPlanner += '</div>';
                htmlTravelPlanner += '</div>';
              htmlTravelPlanner += '</li>';

              ///////////////////////////////////////////////////////////////
            htmlTravelPlanner += '</ol>';

          htmlTravelPlanner += '</div>';
        htmlTravelPlanner += '</div>';

      htmlTravelPlanner += '</div>';
      htmlTravelPlanner += '<div class="col-md-4">';

        htmlTravelPlanner += '<div class="plannerDay">';
          htmlTravelPlanner += '<h2>DAY 3</h2>';
          htmlTravelPlanner += '<div class="plannerDayPlanner gzznestable dd">';

            htmlTravelPlanner += '<ol class="dd-list">';
              ///////////////////////////////////////////////////////////////
              htmlTravelPlanner += '<li class="dd-item" data-id="18">';
                htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                  htmlTravelPlanner += '<div class="dd-content">';
                    htmlTravelPlanner += '<div class="nestableActions">';
                      htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="18" ><i class="fa fa-trash"></i></button>';
                    htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '</div>';
                  htmlTravelPlanner += '<div class="dd-handle">';
                    htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                    htmlTravelPlanner += 'ITEM 18';
                  htmlTravelPlanner += '</div>';
                htmlTravelPlanner += '</div>';
              htmlTravelPlanner += '</li>';
              ///////////////////////////////////////////////////////////////
            htmlTravelPlanner += '</ol>';

          htmlTravelPlanner += '</div>';
        htmlTravelPlanner += '</div>';

      htmlTravelPlanner += '</div>';
    htmlTravelPlanner += '</div>';
  htmlTravelPlanner += '</div>';
htmlTravelPlanner += '</div>';

$('#travelPlannerSec').html(htmlTravelPlanner);

$('.gzznestable.dd').nestable({
  'maxDepth': 1,
  'dragClass': "gzznestable dd-dragel",
  callback: function(l, e) {

    $('.gzznestable').each(function( index ) {
      console.log('DAY'+index)
      console.log($(this).nestable('serialize'));
    });


  }
});

*/
