<?php
namespace johnnymcweed\event\frontend;

/**
 * Event Module.
 *
 * Displays events: Event details page, event overview page and event categories.
 *
 * @author Alexander Schmid <alex.schmid@stud.unibas.ch>
 * @since 1.0.0
 */
class Module extends \luya\admin\base\Module
{
    /**
     * If true, views will be looked up in the @app/views folder.
     * @var bool
     */
    public $useAppViewPath = true;

    /**
     * Default order for upcoming events
     * @var array
     */
    public $comingEventDefaultOrder = [
        'event_start' => SORT_ASC
    ];

    /**
     * Default page size for upcoming events
     * @var int
     */
    public $comingEventDefaultPageSize = 10;

    /**
     * Default order for past events
     * @var array
     */
    public $pastEventDefaultOrder = [
        'event_end' => SORT_DESC
    ];

    /**
     * Default page size for past events
     * @var int
     */
    public $pastEventDefaultPageSize = 10;

    /**
     * Default order for current events
     * @var array
     */
    public $currentEventDefaultOrder = [
        'event_start' => SORT_ASC
    ];

    /**
     * Default page size for current events
     * @var int
     */
    public $currentEventDefaultPageSize = 10;

    /**
     * Default order for current events
     * @var array
     */
    public $allEventDefaultOrder = [
        'event_start' => SORT_ASC
    ];

    /**
     * Default page size for current events
     * @var int
     */
    public $allEventDefaultPageSize = 10;

    /**
     * Default page size for categories
     * @var integer Default number of pages.
     */
    public $categoriesDefaultPageSize = 10;

    /**
     * Default order for category
     * @var array
     */
    public $categoriesDefaultOrder = [
        'title' => SORT_ASC
    ];

    /**
     * The routes for this module
     * @var array
     */
    public $urlRules = [
        ['pattern' => 'events/', 'route' => 'event/default/events'],
        ['pattern' => 'event/', 'route' => 'event/default/events'],
        ['pattern' => 'event/categories/', 'route' => 'event/default/categories'],
        ['pattern' => 'events/categories/', 'route' => 'event/default/categories'],
        ['pattern' => 'event/category/<id:\d+>/<title:[a-zA-Z0-9\-]+>/', 'route' => 'event/default/category'],
        ['pattern' => 'events/category/<id:\d+>/<title:[a-zA-Z0-9\-]+>/', 'route' => 'event/default/category'],
        ['pattern' => 'event/<id:\d+>/<title:[a-zA-Z0-9\-]+>/', 'route' => 'event/default/event'],
        ['pattern' => 'events/<type:[a-zA-Z0-9\-]+>', 'route' => 'event/default/events'],
    ];
}
?>