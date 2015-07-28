require.config({
    paths: {
        suluevent: '../../suluevent/js'
    }
});

define({

    name: "SuluEventBundle",

    initialize: function (app) {

        'use strict';

        var sandbox = app.sandbox;

        app.components.addSource('suluevent', '/bundles/suluevent/js/components');

        function getContentLanguage() {
            return sandbox.sulu.getUserSetting('contentLanguage') || sandbox.sulu.user.locale;
        }

        // list of events
        sandbox.mvc.routes.push({
            route: 'events',
            callback: function () {
                var locale = getContentLanguage();
                this.html('<div data-aura-component="event@suluevent" data-aura-display="list" data-aura-locale="' + locale + '" data-aura-preview="false"/>');
            }
        });

        sandbox.mvc.routes.push({
            route: 'events/:locale/add',
            callback: function (locale) {
                this.html('<div data-aura-component="event@suluevent" data-aura-display="tab" data-aura-content="content" data-aura-locale="' + locale + '"  data-aura-preview="false"/>');
            }
        });

        // show form for editing a content
        sandbox.mvc.routes.push({
            route: 'events/:locale/edit::id/:content',
            callback: function (locale, id, content) {
                this.html(
                    '<div data-aura-component="event@suluevent" data-aura-locale="' + locale + '" data-aura-display="tab" data-aura-content="' + content + '" data-aura-id="' + id + '" data-aura-preview="false"/>'
                );
            }
        });
    }
});
