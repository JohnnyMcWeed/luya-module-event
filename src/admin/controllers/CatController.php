<?php

namespace johnnymcweed\event\admin\controllers;

/**
 * Cat Controller.
 *
 * @author Alexander Schmid <alex.schmid@stud.unibas.ch>
 * @since 1.0.0
 */
class CatController extends \luya\admin\ngrest\base\Controller
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'app\modules\event\models\Cat';
}