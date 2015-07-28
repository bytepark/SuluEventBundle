define([
    'mvc/relationalmodel'
], function (RelationalModel) {

    'use strict';

    return RelationalModel({
        urlRoot: '/admin/api/entryfee',

        defaults: function () {
            return {
                id: null,
                validUntilDate: '',
                price: '',
                event: null
            };
        }
    });
});
