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
        parse: function(res){
            var content;
            switch(this.attributes.filterID){
                case Config.FILTER_LANGUAGE:
                    content = this.parseLanguage(res.languages.available);
                    break;
                case Config.FILTER_EVENT_TYPES:
                    content = this.parseObjectKeyValue(res,'desc');
                    break;
                case Config.FILTER_DEVICE_TYPE:
                    content = this.parseObjectKeyValue(res.devices,'name');
                    break;
                case Config.FILTER_SECTION:
                    content = this.parseObjectKeyValue(res.sections,'name');
                    break;
                case Config.FILTER_EXPLORER:
                    content = this.parseObjectKeyValue(res,'name');
                    break;
                case Config.FILTER_RESOURCE:
                    content = this.parseListObject(res,'title_es');
                    break;
                case Config.FILTER_RESOURCE_TERMS:
                case Config.FILTER_TAXONOMY_TERMS:
                case Config.FILTER_RESOURCE_TOPICS:
                case Config.FILTER_RESOURCE_TYPES:
                    content = this.parseListObject(res,'name_es');
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
        parseObjectKeyValue: function(devsec,valueName){
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
        },
        parseListObject: function(res,valueName){
            var content = [];
            _.each(res,function(resource){
                var r = _.pick(resource,'id',valueName);
                content.push({
                    id: r.id,
                    name: r[valueName]
                });
            });
            return content;
        }
    });
    return FilterModel;
});
