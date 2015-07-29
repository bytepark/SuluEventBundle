# Sulu EventBundle

## What does it do?

This bundle allows plug & play integration into a Sulu CMF instance for collecting events.

It has a strong integration into Sulu by using its category and media management sub-systems
and all the APIs provided to integrate it nicely into the backend.

The frontend output can be freely integrated by using the shipped page template (see Integration 
chapter) and is dispatched via ESI tags (for the list view) and a standalone route for the detail
view.

## Integration

### Kernel

Add the EventBundle to the AbstractKernel in order to use its back- and frontend modules:

    // ... more bundles above
    
    new Sulu\Bundle\EventBundle\SuluEventBundle(),
    
    // ... more bundles below

### Routing

The routing must be configured for both, website and admin contexts. Just add the lines below to
the appropriate files.

#### app/config/admin/routing.yml

    event_bundle:
        resource: "@SuluEventBundle/Resources/config/routing.yml"
        prefix: /admin/events
    
    event_bundle_api:
        resource: "@SuluEventBundle/Resources/config/routing_api.yml"
        prefix: /admin/api
    
    # for preview
    event_bundle_website:
        resource: "@SuluEventBundle/Resources/config/routing_website.yml"

#### app/config/website/routing.yml

    event_bundle:
        resource: "@SuluEventBundle/Resources/config/routing_website.yml"

### Plug & play for editors

To let editors in the backend easily place the list output for events, you just have to add the following
lines to the file `app/config/sulu.yml`.

    sulu_core:
        ...
        content:
            structure:
                ...
                paths:
                    ...
                    sulu_event:
                        path: "%kernel.root_dir%/../vendor/sulu/event-bundle/Resources/pages"
                        internal: false
                        type: "page"

### Configuration

In order to use the LoadEvents data fixture, you have to provide a valid Google Maps Api key and the path
to the CSV file to import. Add the following setup to your `config.yml`:

    sulu_event:
        google_maps_api_key: xxx
        sulu_event.csv_import_file: '%kernel.root_dir%/../data/my.csv'

You can find the format of the CSV file in `DataFixtures/Events/events.csv`. 

## API

### Filters

The frontend filtering currently supports the following criteria:

*   searchString

    If the criteria lat and long are not given, the search is expanded to the city field of the event

*   eventIds 

    CSV list of event ids

*   isTopEvent

    Boolean flag only top events are shown

*   categories
 
    CSV list of category ids

*   dateFrom

    \DateTime compatible date format

*   dateTo

    \DateTime compat date format

*   lat

    Latitude

*   long

    Longitude

*   area

    perimeter (german: "Umkreis") in kilometers (e.g. "50" for a 50km perimeter search)
