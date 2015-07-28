define(function () {

    'use strict';

    return {
        view: true,

        layout: {
            content: {
                width: 'max',
                leftSpace: false,
                rightSpace: false
            }
        },

        header: function () {
            return {
                title: 'sulu.navigation.events',
                noBack: true,
                breadcrumb: [
                    {title: 'sulu.navigation.events'}
                ]
            };
        },

        templates: ['/admin/events/template/event/list'],

        initialize: function () {
            this.bindCustomEvents();
            this.render();
        },

        bindCustomEvents: function () {
            this.sandbox.on('sulu.list-toolbar.delete', this.deleteSelected.bind(this));
        },

        render: function () {
            this.sandbox.dom.html(this.$el, this.renderTemplate(this.templates[0]));

            this.sandbox.sulu.initListToolbarAndList.call(this, 'eventFields', '/admin/api/events/fields',
                {
                    el: '#list-toolbar-container',
                    instanceName: 'events',
                    inHeader: true
                },
                {
                    el: this.sandbox.dom.find('#events-list', this.$el),
                    url: '/admin/api/events?flat=true',
                    searchInstanceName: 'events',
                    searchFields: ['title', 'description', 'zip', 'city'],
                    resultKey: 'events',
                    viewOptions: {
                        table: {
                            icons: [
                                {
                                    icon: 'pencil',
                                    column: 'id',
                                    align: 'left',
                                    callback: function (id) {
                                        this.sandbox.emit('sulu.event.load', id);
                                    }.bind(this)
                                }
                            ],
                            fullWidth: true
                        }
                    }
                }
            );
        },

        deleteSelected: function () {
            this.sandbox.emit('husky.datagrid.items.get-selected', function (events) {
                this.sandbox.emit('sulu_event.events.delete', events, function (deletedId) {
                    this.sandbox.emit('husky.datagrid.record.remove', deletedId);
                }.bind(this), function () {
                    this.sandbox.emit('sulu.labels.success.show', 'labels.success.article-delete-desc', 'labels.success');
                }.bind(this));
            }.bind(this));
        }
    };
});
