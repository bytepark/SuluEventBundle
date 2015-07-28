define([
    'mvc/relationalmodel',
    'mvc/hasone',
    'mvc/hasmany',
    'sulucategory/model/category',
    'sulumedia/model/media',
    'suluevent/models/eventOrganizer',
    'suluevent/models/entryFee'
], function (RelationalModel, HasOne, HasMany, Category, Media, EventOrganizer, EventEntryFee) {

    'use strict';

    function getUrl(urlRoot, id, locale) {
        return urlRoot + ((id !== undefined && id !== null) ? '/' + id : '') + '?locale=' + locale;
    }

    return RelationalModel({
        urlRoot: '/admin/api/events',

        defaults: function () {
            return {
                id: null,
                isTopEvent: false,
                title: '',
                teaser: [],
                startDate: null,
                startTime: null,
                endDate: null,
                description: '',
                website: '',
                categories: [],
                venueDescription: '',
                zip: '',
                city: '',
                country: '',
                latitude: '',
                longitude: '',
                eventOrganizer: [],
                eventEntryFee: []
            };
        },

        relations: [
            {
                type: HasOne,
                key: 'teaser',
                relatedModel: Media
            },
            {
                type: HasMany,
                key: 'categories',
                relatedModel: Category
            },
            {
                type: HasOne,
                key: 'eventOrganizer',
                relatedModel: EventOrganizer
            },
            {
                type: HasMany,
                key: 'eventEntryFee',
                relatedModel: EventEntryFee
            }
        ],

        fetchLocale: function (locale, options) {
            options = _.defaults((options || {}),
                {
                    url: getUrl(this.urlRoot, this.get('id'), locale)
                }
            );

            return this.fetch.call(this, options);
        },

        saveLocale: function (locale, options) {
            options = _.defaults((options || {}),
                {
                    url: getUrl(this.urlRoot, this.get('id'), locale)
                }
            );

            return this.save.call(this, null, options);
        },

        deleteLocale: function (locale, options) {
            options = _.defaults((options || {}),
                {
                    url: getUrl(this.urlRoot, this.get('id'), locale)
                });

            return this.destroy.call(this, options);
        }
    });
});
