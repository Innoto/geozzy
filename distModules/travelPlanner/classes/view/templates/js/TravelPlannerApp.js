var geozzy = geozzy || {};


geozzy.travelPlanner = function() {

  var that = this;

  that.travelPlannerRouter = false;

  // First Execution
  //
  that.init = function(  ) {
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
                  htmlTravelPlanner += '<li class="dd-item" data-id="12">';
                    htmlTravelPlanner += '<div class="dd-item-container clearfix">';
                      htmlTravelPlanner += '<div class="dd-content">';
                        htmlTravelPlanner += '<div class="nestableActions">';
                	        htmlTravelPlanner += '<button class="btnDelete btn-icon btn-danger" data-id="12" ><i class="fa fa-trash"></i></button>';
                	      htmlTravelPlanner += '</div>';
                  	  htmlTravelPlanner += '</div>';
                      htmlTravelPlanner += '<div class="dd-handle">';
                        htmlTravelPlanner += '<i class="fa fa-arrows icon-handle"></i>';
                        htmlTravelPlanner += 'ITEM 12';
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

    $('.bodyContent').html(htmlTravelPlanner);

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
  }

  that.travelPlannerRouter = new geozzy.travelPlannerComponents.mainRouter();

  $(document).ready( function(){
    if( !Backbone.History.started ){
      Backbone.history.start();
    }
  });
}