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
    that.render();
  },

  render: function() {
    var that = this;
    that.listStoryTemplate = _.template( geozzy.storystepsComponents.StorystepslistTemplate );
    that.$el.html(that.listStoryTemplate(geozzy.storiesInstance.listStoryView.stories.get(that.story).toJSON()));
    that.stepList = $("#storyStepsList");

    that.stepList.html('');

    that.storysteps.fetchById(that.story, function(){
      that.listStoryStepItemTemplate = _.template( geozzy.storystepsComponents.StorystepTemplate );
      _.each( that.storysteps.toJSON() , function(item){
        that.stepList.append( that.listStoryStepItemTemplate(item) );
      });
    });
  },

  updateList: function() {
    var that = this;
    console.log('updateList')
    this.listTemplate = _.template( geozzy.storystepsComponents.StorystepslistTemplate );
    this.$el.find('.story').html('');
    var rs = that.storysteps.search({deleted:0});
    rs.sortByField('weight');
    _.each( rs.toJSON() , function(item){
      that.$el.find('.story').append( that.listTemplate({ resource: item }) );
    });

    this.$el.find('.dd').nestable({
      'maxDepth': 1,
      'dragClass': "gzznestable dd-dragel",
      callback: function(l, e) {
        that.saveList();
      }
    });

  },

  saveList: function(){
    console.log('saveList')
    var that = this;
    var jsonNestableResult = $('#storyStepsList').nestable('serialize');
    var itemWeight = 0;
    _.each( jsonNestableResult , function( e , i ){
      var element = that.storysteps.get(e.id);
      element.set({ weight: itemWeight });
      itemWeight++;
    });

    that.saveChangesVisible(true);
  },



  removeStoryStep: function( el ) {
    console.log('removeStoryStep'+el)
    var that = this;

    var rId = parseInt($(el.currentTarget).attr('data-id'));
    var rs = that.storysteps.get( rId );
    rs.set({deleted:1})
    that.updateList();
    that.saveChangesVisible(true);
   },

  addStoryStep: function() {
    var that = this;
    //Backbone.history.start();
    Backbone.history.navigate('/storysteps/story/' + that.story +'/add', {trigger:true})
  },

  saveStoryStep: function() {
    var that = this;
    console.log('saveStoryStep')
    that.storysteps.save(that.story.id);
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
