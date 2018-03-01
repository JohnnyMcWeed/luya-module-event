# Event Module
 
The event module gives the possibility to add events to a Luya application. Therefore events can be added in the backend, which get shown on the frontend afterwards.
It is possible to list all, past, current and future events.
It is possible to show all categories or a single category.
 
## Installation

For the installation of modules Composer is required.

```sh
composer require johnnymcweed/luya-module-event:dev-master 
```

### Configuration

```php
return [
    'modules' => [
        // ...
        'event' => 'johnnymcweed\event\frontend\Module',
        'eventadmin' => 'johnnymcweed\event\admin\Module',
        // ...
    ],
];
```

### Initialization 

After successfully installation and configuration run the migrate, import and setup command to initialize the module in your project.

1.) Migrate your database.

```sh
./vendor/bin/luya migrate
```

2.) Import the module and migrations into your LUYA project.

```sh
./vendor/bin/luya import
```

After adding the persmissions to your group you will be able to edit and add new news articles.

## Example Views

There are default views set up. Use these or create your own custom views.
