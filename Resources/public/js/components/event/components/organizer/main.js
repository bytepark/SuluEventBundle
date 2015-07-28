define([], function () {

    'use strict';

    var formSelector = '#event-organizer';

    return {
        name: 'Sulu Event Organizer',

        view: true,

        layout: {
            content: {
                width: 'max'
            }

        },

        templates: ['/admin/events/template/event/organizer'],

        initialize: function () {
            this.initializeValidation();

            this.load();

            this.bindCustomEvents();
        },

        load: function () {
            this.sandbox.emit('sulu.events.get-data', function (data) {
                this.render(data);
            }.bind(this));
        },

        render: function (data) {
            this.data = data;

            this.setHeader();

            this.sandbox.dom.html(this.$el, this.renderTemplate(this.templates[0]));
            this.initForm(data.organizer);
        },

        setHeader: function () {
            this.sandbox.emit('sulu.header.set-toolbar', {
                template: 'default'
            });

            var title = 'sulu.event.events',
                breadcrumb = [
                    {title: 'sulu.navigation.events'}
                ];

            if (!!this.options.data) {
                breadcrumb.push({title: this.data.title});
                breadcrumb.push({title: 'sulu.content-navigation.event.organizer'});
                title = this.data.title;
            }

            this.sandbox.emit('sulu.header.set-title', title);
            this.sandbox.emit('sulu.header.set-breadcrumb', breadcrumb);
        },

        bindCustomEvents: function () {
            this.sandbox.on('sulu.header.back', function () {
                this.sandbox.emit('sulu.router.navigate', 'events');
            }, this);

            this.sandbox.on('sulu.event.saved', function (data) {
                this.data = data;
                this.setHeader();
                this.sandbox.emit('sulu.header.toolbar.item.enable', 'save-button');
            }.bind(this));

            this.sandbox.on('sulu.header.toolbar.save', function () {
                this.save();
            }.bind(this));

            this.sandbox.on('sulu.header.toolbar.delete', function () {
                this.sandbox.emit('sulu.event.delete', this.sandbox.dom.val('#id'));
            }.bind(this));

            this.sandbox.on('husky.toolbar.header.initialized', function () {
                this.sandbox.emit('sulu.header.toolbar.item.enable', 'save-button');
            }.bind(this));
        },

        initializeValidation: function () {
            this.sandbox.form.create(formSelector);
        },

        save: function () {
            if (this.sandbox.form.validate(formSelector)) {
                this.data.organizer = this.sandbox.form.getData(formSelector);

                this.sandbox.emit('sulu.event.save', this.data);
            }
        },

        initForm: function (data) {
            var formObject = this.sandbox.form.create(formSelector);
            formObject.initialized.then(function () {
                this.setFormData(data);
            }.bind(this));
        },

        setFormData: function (data) {
            this.sandbox.form.setData(formSelector, data).then(function () {
                this.sandbox.start(formSelector);
            }.bind(this)).fail(function (error) {
                this.sandbox.logger.error("An error occured when setting data!", error);
            }.bind(this));
        }
    };
});
