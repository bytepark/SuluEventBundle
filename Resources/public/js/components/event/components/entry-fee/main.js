define(['text!suluevent/components/event/components/entry-fee/form.html'], function (EntryFeeForm) {

    'use strict';

    var constants = {

            overlayId: 'entryFeeOverlay',
            entryFeeListSelector: '#event-entry-fee-list',
            entryFeeFormSelector: '#event-entry-fee-form',

            entryFeeURL: '/admin/api/entryfee/'
        },

        /**
         * Template for header toolbar
         * @returns {*[]}
         */
        listTemplate = function () {
            return [
                {
                    id: 'add',
                    icon: 'plus-circle',
                    class: 'highlight-white',
                    title: 'add',
                    position: 10,
                    callback: this.addOrEditEntryFee.bind(this)
                },
                {
                    id: 'settings',
                    icon: 'gear',
                    items: [
                        {
                            id: 'delete',
                            title: this.sandbox.translate('sulu.event.entryfee.remove'),
                            callback: this.removeEntryFee.bind(this),
                            disabled: true
                        }
                    ]
                }
            ];
        };

    return {

        view: true,

        layout: {
            content: {
                width: 'max'
            }

        },

        templates: ['/admin/events/template/event/entry-fee.list'],

        initialize: function () {
            this.load();

            this.bindCustomEvents();
        },

        load: function () {
            this.sandbox.emit('sulu.events.get-data', function (data) {
                this.render(data);
            }.bind(this));
        },

        bindCustomEvents: function () {
            // back to list
            this.sandbox.on('sulu.header.back', function () {
                this.sandbox.emit('sulu.router.navigate', 'events');
            }, this);

            // update entry fee
            this.sandbox.on('sulu.event.entryFee.updated',
                function (model) {
                    this.sandbox.emit('husky.datagrid.records.change', model);
                }, this);

            // remove record from datagrid
            this.sandbox.on('sulu.event.entryFee.removed',
                function (id) {
                    this.sandbox.emit('husky.datagrid.record.remove', id);
                }, this);

            // add new entry fee
            this.sandbox.on('sulu.event.entryFee.added',
                function (model) {
                    this.sandbox.emit('husky.datagrid.record.add', model);
                }, this);

            // loaded entry fee
            this.sandbox.on('sulu.event.entryFee.loaded',
                function (item) {
                    this.startOverlay(item);
                }, this);

            // edit entry fee
            this.sandbox.on('husky.datagrid.item.click', function (id) {
                this.sandbox.emit('sulu.event.entryFee.load', id);
            }, this);

            this.sandbox.on('husky.overlay.entryFee-add-edit.opened', function () {
                // start components in overlay
                this.sandbox.start(constants.entryFeeFormSelector);
                var formObject = this.sandbox.form.create(constants.entryFeeFormSelector);

                formObject.initialized.then(function () {
                    this.sandbox.form.setData(constants.entryFeeFormSelector, this.overlayData);
                }.bind(this));
            }.bind(this));
        },

        setHeader: function () {
            var title = 'sulu.event.events',
                breadcrumb = [
                    {title: 'sulu.navigation.events'}
                ];

            if (!!this.data) {
                breadcrumb.push({title: this.data.title});
                breadcrumb.push({title: 'sulu.content-navigation.event.entry_fee'});
                title = this.data.title;
            }

            this.sandbox.emit('sulu.header.set-title', title);
            this.sandbox.emit('sulu.header.set-breadcrumb', breadcrumb);
        },

        /**
         * Inits the process to add or edit an entry fee
         */
        addOrEditEntryFee: function (id) {
            if (!!id) {
                this.sandbox.emit('sulu.event.entryFee.load', id);
            } else {
                this.startOverlay(null);
            }
        },

        /**
         * starts overlay to edit / add entry fee
         */
        startOverlay: function (data) {

            var translation, entryFeeTemplate, $container, values;

            this.sandbox.dom.remove('#' + constants.overlayId);
            $container = this.sandbox.dom.createElement('<div id="' + constants.overlayId + '"></div>');
            this.sandbox.dom.append(constants.entryFeeListSelector, $container);

            this.overlayData = data;

            if (!!data && !!data.id) {
                translation = this.sandbox.translate('sulu.event.entryfee.edit');
            } else {
                translation = this.sandbox.translate('sulu.event.entryfee.add');
            }

            values = {
                translate: this.sandbox.translate,
                eventId: (!!this.data) ? this.data.id : ''
            };

            entryFeeTemplate = this.sandbox.util.template(EntryFeeForm, values);

            this.sandbox.start([
                {
                    name: 'overlay@husky',
                    options: {
                        el: $container,
                        title: translation,
                        openOnStart: true,
                        removeOnClose: true,
                        instanceName: 'entryFee-add-edit',
                        data: entryFeeTemplate,
                        skin: 'wide',
                        okCallback: this.editAddOkClicked.bind(this),
                        closeCallback: this.stopOverlayComponents.bind(this)
                    }
                }
            ]);
        },

        /**
         * Stops subcomponents of overlay
         */
        stopOverlayComponents: function () {
            this.sandbox.stop(constants.entryFeeFormSelector);
        },

        /**
         * triggered when overlay was closed with ok
         */
        editAddOkClicked: function () {
            if (this.sandbox.form.validate(constants.entryFeeFormSelector, true)) {
                var data = this.sandbox.form.getData(constants.entryFeeFormSelector);

                if (!data.id) {
                    delete data.id;
                }

                this.sandbox.emit('sulu.event.entryFee.save', data);
                this.stopOverlayComponents();
            } else {
                return false;
            }
        },

        render: function (data) {
            this.data = data;

            this.setHeader();

            var url = '/admin/api/entryfee?flat=true&event=' + this.data.id;

            this.sandbox.dom.html(this.$el, this.renderTemplate('/admin/events/template/event/entry-fee.list'));

            // init list-toolbar and datagrid
            this.sandbox.sulu.initListToolbarAndList.call(this, 'entryFeeFields', '/admin/api/entryfee/fields',
                {
                    el: this.$find('#list-toolbar-container'),
                    instanceName: 'entryFee',
                    inHeader: true,
                    hasSearch: false,
                    template: listTemplate.call(this)
                },
                {
                    el: this.sandbox.dom.find('#event-entry-fee-list', this.$el),
                    url: url,
                    resultKey: 'entryfee',
                    viewOptions: {
                        table: {
                            fullWidth: true
                        }
                    }
                }
            );
        },

        /**
         * Removes elements from datagrid
         */
        removeEntryFee: function () {
            this.sandbox.emit('husky.datagrid.items.get-selected', function (ids) {
                if (ids.length > 0) {
                    this.sandbox.emit('sulu.event.entryFee.delete', ids);
                }
            }.bind(this));
        }
    };
});
