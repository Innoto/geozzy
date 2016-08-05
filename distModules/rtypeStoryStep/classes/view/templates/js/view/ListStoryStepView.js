var geozzy = geozzy || {};
if(!geozzy.storystepsComponents) geozzy.storystepsComponents={};
geozzy.storystepsComponents.ListStoryStepView = Backbone.View.extend({

  el : "#storyStepListPage",
  tagName : '',
  story: false,
  listStoryTemplate: false,
  listStoryStepItemTemplate : false,

  events: {
    "click .addStoryStep": "addStoryStep" ,
    "click .btnEditStoryStep" : "editStoryStep",
    "click .btnDelete" : "removeStoryStep" ,
    "click .cancel" : "cancelStoryStep" ,
    "click .save" : "saveStoryStep"
  },

  story: false,
  storysteps : false,

  initialize: function( story ) {
    var that = this;
    that.story = story;
    that.storysteps = new geozzy.storystepsComponents.StorystepCollection();

    that.storysteps.fetchById(that.story, function(){
      that.updateList();
    });

    that.render();

  },

  render: function() {
    var that = this;
    that.listStoryTemplate = _.template( geozzy.storystepsComponents.StorystepslistTemplate );
    that.$el.html(that.listStoryTemplate(geozzy.storiesInstance.listStoryView.stories.get(that.story).toJSON()));
    that.saveChangesVisible(false);
  },

  updateList: function() {
    var that = this;
    this.listStoryStepItemTemplate = _.template( geozzy.storystepsComponents.StorystepTemplate );
    that.stepList = $("#storyStepsList");
    that.stepList.html('');
    var steps = that.storysteps.search({deleted:0});
    steps.sortByField('weight');
    _.each( steps.toJSON() , function(item){
      that.stepList.append( that.listStoryStepItemTemplate({ id: item.get('id'), title: item.get('title') }) );
    });
    $("#storyStepsList.dd").nestable({
      'maxDepth': 1,
      'dragClass': "gzznestable dd-dragel",
      callback: function(l, e) {
        that.saveList();
      }
    });

  },

  saveList: function(){

    var that = this;
    var jsonNestableResult = $("#storyStepsList").nestable('serialize');
    var itemWeight = 0;
    _.each( jsonNestableResult , function( e , i ){

      var element = that.storysteps.get(e.id);
      element.set({ weight: itemWeight });
      itemWeight++;
    });
    that.saveChangesVisible(true);
  },

  editStoryStep: function( el ) {
    var that = this;
    Backbone.history.navigate('/resource/edit/id/'+ $(el.currentTarget).attr('data-id') + '/story/' + that.story, {trigger:true});
  },

  removeStoryStep: function( el ) {
    var that = this;
    var rId = parseInt($(el.currentTarget).attr('data-id'));
    var deletedStep = that.storysteps.get( rId );
    deletedStep.set({deleted:1})
    that.updateList();
    that.saveChangesVisible(true);
   },

  addStoryStep: function() {
    var that = this;
    Backbone.history.navigate('/storysteps/story/' + that.story +'/assign', {trigger:true})
  },

  saveStoryStep: function() {
    var that = this;
    that.storysteps.save(that.story);
    console.log('saveStoryStep')
    that.updateList();
    that.saveChangesVisible(false);
  },

  cancelStoryStep: function() {
    var that = this;
    that.saveChangesVisible(false);
    that.initialize( that.story );
  },

  saveChangesVisible: function( visible ) {
    if( visible ) {
      this.$el.find('.saveChanges').show();
    }
    else{
      this.$el.find('.saveChanges').hide();
    }

  }
});
