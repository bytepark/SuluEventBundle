define([
    'suluevent/models/event',
    'suluevent/models/entryFee',
    'app-config',
    'sulucategory/model/category'
], function (SuluEvent, EntryFee, AppConfig,
        Category) {

    'use strict';

    return {

        initialize: function () {
            this.suluEvent = new SuluEvent();

            this.bindCustomEvents();

            if (this.options.display === 'list') {
                this.renderList();
            } else if (this.options.display === 'tab') {
                this.renderTabs();
            } else {
                throw 'display type wrong';
            }
        },

        bindCustomEvents: function () {
            this.sandbox.on('sulu.list-toolbar.add', function () {
                this.sandbox.emit(
                    'sulu.router.navigate',
                    'events/' + AppConfig.getUser().locale + '/add'
                );
            }.bind(this));

            this.sandbox.on('sulu.event.load', function (id) {
                this.load(id, AppConfig.getUser().locale);
            }.bind(this));

            this.sandbox.on('sulu.event.save', function (data) {
                this.save(data);
            }.bind(this));

            this.sandbox.on('sulu.events.get-data', function(callback) {
                if (!!this.data) {
                    callback(JSON.parse(JSON.stringify(this.data)));
                } else {
                    callback({});
                }
            }.bind(this));

            this.sandbox.on('sulu_event.events.delete', this.deleteEvents.bind(this));
            this.sandbox.on('sulu.event.delete', this.deleteEvent.bind(this));

            this.sandbox.on('sulu.event.entryFee.delete', this.removeEntryFee.bind(this));
            this.sandbox.on('sulu.event.entryFee.save', this.saveEntryFee.bind(this));
            this.sandbox.on('sulu.event.entryFee.load', this.loadEntryFee.bind(this));
        },

        load: function (id, localization) {
            this.sandbox.emit('sulu.router.navigate', 'events/' + localization + '/edit:' + id + '/details');
        },

        renderList: function () {
            var $list = this.sandbox.dom.createElement('<div id="event-list-container"/>');
            this.html($list);
            this.sandbox.start([
                {name: 'event/components/list@suluevent', options: {el: $list}}
            ]);
        },

        renderTabs: function () {
            this.suluEvent = new SuluEvent();

            var $tabContainer = this.sandbox.dom.createElement('<div/>'),
                component = {
                    name: 'event/components/content@suluevent',
                    options: {
                        el: $tabContainer,
                        locale: this.options.locale
                    }
                },
                dfd = this.sandbox.data.deferred();

            this.html($tabContainer);

            if (!!this.options.id) {
                component.options.content = this.options.content;
                component.options.id = this.options.id;
                this.suluEvent = new SuluEvent({id: this.options.id});
                this.suluEvent.fetchLocale(this.options.locale, {
                    success: function (model) {
                        this.data = model.toJSON();
                        component.options.data = this.data;
                        this.sandbox.start([component]);
                        dfd.resolve();
                    }.bind(this),
                    error: function () {
                        this.sandbox.logger.error("error while fetching event");
                        dfd.reject();
                    }.bind(this)
                });
            } else {
                this.sandbox.start([component]);
                dfd.resolve();
            }

            return dfd.promise();
        },

        save: function (data) {
            this.sandbox.emit('sulu.header.toolbar.item.loading', 'save-button');

            this.suluEvent = new SuluEvent(data);
            this.suluEvent.get('categories').reset();
            this.sandbox.util.foreach(data.categories,function(id){
                var category = Category.findOrCreate({id: id});
                this.suluEvent.get('categories').add(category);
            }.bind(this));

            this.suluEvent.saveLocale(this.options.locale, {
                success: function (response) {
                    this.data = response.toJSON();

                    if (!!this.data.id) {
                        this.sandbox.emit('sulu.event.saved', this.data);
                    } else {
                        this.load(this.data.id, this.options.locale);
                    }
                }.bind(this),
                error: function () {
                    this.sandbox.logger.log('error while saving event');
                }.bind(this)
            });
        },

        deleteEvents: function (eventIds, callback, finishedCallback) {
            var event, count = 0;
            this.sandbox.sulu.showDeleteDialog(function (confirmed) {
                if (confirmed === true) {
                    this.sandbox.util.foreach(eventIds, function (id) {
                        event = new SuluEvent({id: id});
                        event.deleteLocale(this.options.locale, {
                            success: function () {
                                if (typeof callback === 'function') {
                                    callback(id);
                                }
                                count++;
                                if (count === eventIds.length && typeof finishedCallback === 'function') {
                                    finishedCallback();
                                }
                            }.bind(this),
                            error: function () {
                                this.sandbox.logger.log('Error while deleting a single event');
                            }.bind(this)
                        });
                    }.bind(this));
                }
            }.bind(this));
        },

        deleteEvent: function (eventId) {
            var event;
            this.sandbox.sulu.showDeleteDialog(function (confirmed) {
                if (confirmed === true) {
                    event = new SuluEvent({id: eventId});
                    event.deleteLocale(this.options.locale, {
                        success: function () {
                            this.sandbox.emit(
                                'sulu.router.navigate',
                                'events'
                            );
                        }.bind(this),
                        error: function () {
                            this.sandbox.logger.log('Error while deleting a single event');
                        }.bind(this)
                    });
                }
            }.bind(this));
        },

        saveEntryFee: function (data) {
            var isNew = true;
            if (!!data.id) {
                isNew = false;
            }

            this.entryFee = EntryFee.findOrCreate({id: data.id});
            this.entryFee.set(data);
            this.entryFee.save(null, {
                // on success save contacts id
                success: function (response) {
                    this.entryFee = response.toJSON();
                    if (!!isNew) {
                        this.sandbox.emit('sulu.event.entryFee.added', this.entryFee);
                    } else {
                        this.sandbox.emit('sulu.event.entryFee.updated', this.entryFee);
                    }

                }.bind(this),
                error: function () {
                    this.sandbox.logger.log("error while saving entry fee");
                }.bind(this)
            });
        },

        removeEntryFee: function (ids) {
            this.confirmDeleteDialog(function (wasConfirmed) {
                if (wasConfirmed) {
                    var entryFee;
                    this.sandbox.util.foreach(ids, function (id) {
                        entryFee = EntryFee.findOrCreate({id: id});
                        entryFee.destroy({
                            success: function () {
                                this.sandbox.emit('sulu.event.entryFee.removed', id);
                            }.bind(this),
                            error: function () {
                                this.sandbox.logger.log("error while deleting entry fee");
                            }.bind(this)
                        });
                    }.bind(this));
                }
            }.bind(this));
        },

        confirmDeleteDialog: function (callbackFunction) {
            this.sandbox.emit(
                'sulu.overlay.show-warning',
                'sulu.overlay.be-careful',
                'sulu.overlay.delete-desc',
                callbackFunction.bind(this, false),
                callbackFunction.bind(this, true)
            );
        },

        loadEntryFee: function (id) {
            if (!!id) {
                this.entryFee = EntryFee.findOrCreate({id: id});
                this.entryFee.fetch({
                    success: function (model) {
                        this.entryFee = model;
                        this.sandbox.emit('sulu.event.entryFee.loaded', model.toJSON());
                    }.bind(this),
                    error: function (e1, e2) {
                        this.sandbox.logger.log('error while fetching entry fee', e1, e2);
                    }.bind(this)
                });
            } else {
                this.sandbox.logger.warn('no id given to load entry fee');
            }
        }
    };
});
