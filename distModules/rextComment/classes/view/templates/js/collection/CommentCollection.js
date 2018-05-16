var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.CommentCollection = Backbone.Collection.extend({
  initialize: function(models, options) {
    this.resource = options.resource;
  },
  url: function() {
    return '/'+cogumelo.publicConf.C_LANG+'/api/comments/false/resources/'+this.resource;
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
