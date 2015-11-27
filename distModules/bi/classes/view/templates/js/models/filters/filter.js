//### Filter Model: Depending on selected filter, the way for making the request of the values is different
define([
    'underscore',
    'backbone',
    'config/appConfig'
], function (_, Backbone, Config) {
    // URL GET method, and the model contains the filter ID, values, title, url and the Content (with different IDs and Values)
    var FilterModel = Backbone.Model.extend({
        url: function () {
            return this.attributes.url;
        },
        defaults: {
            filterID: '',
            values: [],
            title: '',
            url: '',
            content: []
        },
        // Main function where is chosen the structure type for each filter
        parse: function (res) {
            var content;
            switch (this.attributes.filterID) {
                case Config.FILTER_LANGUAGE:
                    content = this.parseLanguage(res.languages.available);
                    break;
                case Config.FILTER_EVENT_TYPES:
                    content = this.parseObjectKeyValue(res, 'desc');
                    break;
                case Config.FILTER_DEVICE_TYPE:
                    content = this.parseObjectKeyValue(res.devices, 'name');
                    break;
                case Config.FILTER_SECTION:
                    content = this.parseObjectKeyValue(res.sections, 'name');
                    break;
                case Config.FILTER_EXPLORER:
                    content = this.parseObjectKeyValue(res, 'name');
                    break;
                case Config.FILTER_RESOURCE:
                    content = this.parseListObject(res, 'title_es');
                    break;
                case Config.FILTER_RESOURCE_TERMS:
                case Config.FILTER_TAXONOMY_TERMS:
                case Config.FILTER_RESOURCE_TOPICS:
                case Config.FILTER_RESOURCE_TYPES:
                    content = this.parseListObject(res, 'name_es');
                    break;
            }
            this.set('content', content);
        },
        // Parsing method used only for the Language Filter, which is totally different to the others
        //* For each different value on the correct field, the ID and Name are pushed into the Content List
        parseLanguage: function (languages) {
            var content = [];
            _.each(
                _.values(languages),
                function (language) {
                    content.push({
                        id: language.i18n,
                        name: language.name
                    });
                }
            );
            return content;
        },
        // Parsing method used for another different structure, in which for each key, the value is picked from inside
        // of that object
        //* The Key is picked and for each Key, the Value is taken from inside of it. After, the values are pushed
        // into the Content List
        parseObjectKeyValue: function (devsec, valueName) {
            var content = [],
                keys = _.keys(devsec),
                values = _.values(devsec);
            _.each(values, function (value, index) {
                content.push({
                    id: keys[index],
                    name: value[valueName]
                });
            });
            return content;
        },
        // Parsing method used for another different structure, in which there are objects with the key and the value as fields
        //* Simpler loop, where for each object in the list, ID and Name are picked, and finally pushed into the Content List
        parseListObject: function (res, valueName) {
            var content = [];
            _.each(res, function (resource) {
                var r = _.pick(resource, 'id', valueName);
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
