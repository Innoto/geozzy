var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};


geozzy.storyComponents.StoryPluginTimelineView = Backbone.View.extend({
  displayType: 'plugin',
  parentStory: false,
  tplElement: false,

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

      // Create a JSON data table

      // specify options
      var options = {
          /*'width':  '100%',
          'height': '300px',*/
          'editable': false,   // enable dragging and editing events
          'style': 'box',
          /*'locale':'es',*/
          'zoomable': false,
          'unselectable':false,
          'cluster':true
      };

      // Instantiate our timeline object.
      that.timeline = new links.Timeline( $(that.options.container)[0] , options);
      that.timeline.setSelection(3);
  /*
      function onRangeChanged(properties) {
          document.getElementById('info').innerHTML += 'rangechanged ' +
                  properties.start + ' - ' + properties.end + '<br>';
      }
  */
      // attach an event listener using the links events handler
      //links.events.addListener(timeline, 'rangechanged', onRangeChanged);

      // Draw our timeline with the created data and options
      that.timeline.draw(that.getData());
    }

    that.parentStory.bindEvent('stepChange', function(obj){
      that.setStep(obj);
    });

  },

  setStep: function( step ) {
    var that = this;

/*
    var legend = that.parentStory.storySteps.get(step.id).get('legend');

    if( legend != null ) {

      $(that.options.container).html(that.tplElement({id:legend}));
      $(that.options.container).fadeIn();
    }
    else {
      $(that.options.container).fadeOut();
    }

*/

  },

  getData: function( ) {
    var that = this;
    var data = [
        {
            'start': new Date(1800,7,23),
            'content': 'EV1'
        },
        {
            'start': new Date(1900,7,23,23,0,0),
            'content': 'EV2'
        },
        {
            'start': new Date(1850,7,24,16,0,0),
            'end': new Date(1900,7,24,16,0,0),

            'content': 'EV3'
        },

        {
            'start': new Date(1700,7,28),
            'content': 'EV5'
        },

        {
            'start': new Date(2010,8,4,12,0,0),
            'content': 'EV6'
        }
    ];

    return data;
  }

});
