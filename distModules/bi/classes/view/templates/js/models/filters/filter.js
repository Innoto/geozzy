define([
    'underscore',
    'backbone',
    'config/appConfig'
],function(_,Backbone,Config){
    var FilterModel = Backbone.Model.extend({
        url: function(){
            return this.attributes.url;
        },
        defaults: {
            filterID: '',
            values: [],
            title: '',
            url: '',
            content:[]
        },
        initialize: function(){
            //Request made with filterTitle
            //this.settitle
            //Modify template
        },
        parse: function(res){
            var content;
            switch(this.attributes.filterID){
                case Config.FILTER_EVENT_TYPES:
                    content = this.parseDevSec(res,'desc');
                    break;
                case Config.FILTER_DEVICE_TYPE:
                    content = this.parseDevSec(res.devices,'name');
                    break;
                case Config.FILTER_LANGUAGE:
                    content = this.parseLanguage(res.languages.available);
                    break;
                case Config.FILTER_SECTION:
                    content = this.parseDevSec(res.sections,'name');
                    break;
            }
            this.set('content',content);
        },
        parseLanguage: function(languages){
            var content = [];
            _.each(
                _.values(languages),
                function(language){
                    content.push({
                        id: language.i18n,
                        name: language.name
                    });
                }
            );
            return content;
        },
        parseDevSec: function(devsec,valueName){
            var content = [],
                keys = _.keys(devsec),
                values = _.values(devsec);
            _.each(values,function(value,index){
                content.push({
                    id: keys[index],
                    name: value[valueName]
                });
            });
            return content;
        }
    });
    return FilterModel;
});
