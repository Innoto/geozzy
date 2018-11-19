var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryListAccView = Backbone.View.extend({
  displayType: 'list',
  parentStory: false,
  stepsDOM: false,
  stepsDOMEquivalences: false,
  currentDOMStep: false,
  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      container: false,
      maxWidth: 600,
      topMargin: 100,
      bottomMargin:300,
      leftMargin:30,
      rightMargin:30,
      steepMarginsDifference: 160,
      tplElement: geozzy.storyComponents.listElementTemplate
    });

    that.options = $.extend(true, {}, options, opts);


    that.tplElement = _.template(that.options.tplElement);

    that.el = that.options.container;
    that.$el = $(that.el);
  },

  setParentStory: function( obj ) {
    var that = this;

    that.parentStory = obj;
  },

  render: function() {
    var that = this;

    // Calculate distances
    if( that.options.steepMargins ) {

    }
    else
    if( typeof that.parentStory.displays.background != 'undefined') {

    }
    that.stepsDOMEquivalences = [];
    that.$el.html('');
    that.parentStory.storySteps.each( function( step , i ) {
      var d = step.toJSON();
      cogumelo.log(d);
      that.$el.append( that.tplElement( d ) );
      that.stepsDOMEquivalences.push( d.id );
    });

    that.stepsDOM= that.$el.find('.storyStepAcc').toArray();

    that.parentStory.bindEvent('storyReady', function() {
      that.parentStory.triggerEvent('stepChange', {id: that.stepsDOMEquivalences[0] , domElement: that.stepsDOM[0] });
      $('button.accessButton').click( function(ev){
        geozzy.storyComponents.routerInstance.navigate( 'resource/' +$(ev.target).attr('dataResourceAccessButton') , false);
        that.parentStory.triggerEvent( 'loadResource' , $(ev.target).attr('dataResourceAccessButton') );
      });
    });

  }

});
