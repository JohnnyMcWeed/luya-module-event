<?php

namespace johnnymcweed\event\admin\apis;

/**
 * Event Controller.
 * 
 * @author Alexander Schmid <alex.schmid@stud.unibas.ch>
 * @since 1.0.0
 */
class EventController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'johnnymcweed\event\models\Event';
}