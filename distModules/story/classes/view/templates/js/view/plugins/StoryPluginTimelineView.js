var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};


geozzy.storyComponents.StoryPluginTimelineView = Backbone.View.extend({
  displayType: 'plugin',
  parentStory: false,
  tplElement: false,
  timeline :false,
  timelineIndex:[],
  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      container: false,
      //tplElement: '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-id%>/storyLegend/<%-id%>.png" />'
      //tplElement: '<div class==> LOL </div>'
    });
    that.options = $.extend(true, {}, options, opts);


  },

  setParentStory: function( obj ) {
    var that = this;
    that.parentStory = obj;
  },

  render: function() {
    var that = this;

    if( that.options.container !== false) {

      // asignamos locale según idioma
      switch ( cogumelo.publicConf.C_LANG ) {
        case 'es':
            var tl_locale = 'es_ES';
          break;
        case 'gl':
            var tl_locale = 'es_ES';
          break;
        case 'ca':
          var tl_locale = 'ca_ES';
          break;
        case 'fr':
          var tl_locale = 'fr_FR';
          break;
        case 'de':
          var tl_locale = 'de_DE';
          break;

        case 'en':
        default:
          var tl_locale = 'en_US';

      }


      // specify options
      var options = {
          /*'width':  '100%',
          'height': '300px',*/
          'editable': false,   // enable dragging and editing events
          'style': 'box',
          /*'locale':'es',*/
          'zoomable': false,
          'unselectable':false,
          'locale':tl_locale
      };

      // Instantiate our timeline object.
      that.timeline = new links.Timeline( $(that.options.container)[0] , options);

  /*
      function onRangeChanged(properties) {
          document.getElementById('info').innerHTML += 'rangechanged ' +
                  properties.start + ' - ' + properties.end + '<br>';
      }
  */
      // attach an event listener using the links events handler
      links.events.addListener(that.timeline, 'select', function( propiedades ) {
        var selection = that.timeline.getSelection();

        //cogumelo.log(selection[0].row);
        //cogumelo.log(that.timeline.getSelection()[0].row);
        that.parentStory.triggerEvent('forceStep', { id: that.timelineIndex[ selection[0].row ] } );
        //that.timeline.box.align = 'center';
        that.timeline.setVisibleChartRangeAuto();
      });

      // Draw our timeline with the created data and options
      that.timeline.draw(that.getData());
    }

    that.parentStory.bindEvent('stepChange', function(obj){
      that.setStep(obj);
    });

  },

  setStep: function( step ) {
    var that = this;


    var showTimeline = that.parentStory.storySteps.get(step.id).get('showTimeline');

    if( showTimeline != null && showTimeline == 1 ) {
      //$(that.options.container).html(that.tplElement({id:legend}));
      /*cogumelo.log(that.timeline)
      cogumelo.log(that.timeline.getData());
      that.timeline.selectItem(0)*/
      //that.timeline.setSelection([{row: 0}])

      $.each( that.timelineIndex, function(i,e){
        if( e == step.id) {
          that.timeline.selectItem(i);
        }
      });

      //cogumelo.log(that.timeline.getSelection())
      $(that.options.container).fadeIn();
    }
    else {
      $(that.options.container).fadeOut();
    }



  },

  getData: function( ) {
    var that = this;

    var timeLineData = [];
    that.timelineIndex = [];

    that.parentStory.storySteps.each( function(e,i){

      var showTimeline = e.get('showTimeline');

      if( showTimeline != null && showTimeline == 1 ) {
        that.timelineIndex.push(e.get('id'));
        timeLineData.push({
          'start': new Date(e.get('initDate')),
          'end': new Date(e.get('endDate')),
          'content': e.get('title')
        });
      }
    });

    return timeLineData;
  }

});
