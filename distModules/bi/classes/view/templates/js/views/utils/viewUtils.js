'use strict';

define([
        'underscore',
        'views/filters/boxFilter',
        'views/filters/dateFilter',
        'config/appConfig'
], function (_, BoxFilterView, DateFilterView, Config) {

    return {
        getFilterView: function(filterID){
            var listBox = [Config.FILTER_LANGUAGE,Config.FILTER_SECTION,Config.FILTER_EVENT_TYPES,Config.FILTER_DEVICE_TYPE],
                listDate = [Config.FILTER_DATE_RANGE];

            if (_.contains(listBox,filterID)){
                return BoxFilterView;
            }
            if (_.contains(listDate,filterID)){
                return DateFilterView;
            }
        }
    };
});
