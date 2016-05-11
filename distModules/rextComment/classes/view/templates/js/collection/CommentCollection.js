var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.CommentCollection = Backbone.Collection.extend({
  initialize: function(models, options) {
    this.resource = options.resource;
  },
  url: function() {
    return '/api/comment/list/resource/'+this.resource;
  },
  model: geozzy.commentComponents.CommentModel,
  sortKey: 'timeCreation',

  comparator: function(item){
    return item.get(this.sortKey);
  },
  sortByField: function(fieldName) {
    this.sortKey = fieldName;
		this.sort();
  }
});
