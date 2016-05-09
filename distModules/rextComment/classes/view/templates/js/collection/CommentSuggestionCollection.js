var geozzy = geozzy || {};
if(!geozzy.commentComponents) geozzy.commentComponents={};

geozzy.commentComponents.CommentSuggestionCollection = Backbone.Collection.extend({
  initialize: function(models, options) {
    this.resource = options.resource;
  },
  url: function() {
    return '/api/admin/commentsuggestion/list/resource/'+this.resource;
  },
  model: geozzy.commentComponents.CommentModel,
  sortKey: 'timeCreation',


  search: function( opts ){
    var result = this.where( opts );
    var resultCollection = new geozzy.commentComponents.CommentSuggestionCollection(result, {resource: this.resource});

    return resultCollection;
  },
  comparator: function(item){
    return item.get(this.sortKey);
  },
  sortByField: function(fieldName) {
    this.sortKey = fieldName;
		this.sort();
  }
});
