var geozzy = geozzy || {};
if (! geozzy.storyComponents) { geozzy.storyComponents= {} }

geozzy.storyComponents.StoryStepModel = Backbone.Model.extend({
  defaults: {
    id: false,
    img: false,
    title: false,
    description: false
  }

});
