define([
    'backbone'
], function(Backbone){
    var FilterTitleModel = Backbone.Model.extend({
        defaults: {
            filterID: '',
            title: ''
        },
        parse: function(res){
            this.set({
                title: res.title
            });
        }
    });
    return FilterTitleModel;
});
