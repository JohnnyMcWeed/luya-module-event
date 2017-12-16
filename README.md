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

As the module will try to render a view for the news overview, here is what this could look like this in a very basic way:

#### views/event/default/event.php

```php
<?php
/* @var $this \luya\web\View */
/* @var $model \johnnymcweed\event\models\Event */
?>
<h1><?= $model->title; ?></h1>
<span class="infobox"><?= Yii::$app->formatter->asDate($model->event_start) ?> - <?= Yii::$app->formatter->asDate($model->event_end) ?></span>
<?= $model->text ?>
<p><a href="<?= $route = \luya\helpers\Url::toRoute(['/events']); ?>">Back</a></p>
```

#### views/event/default/events.php

```php
<?php
use yii\widgets\LinkPager;
use luya\web\JsonLd;
use luya\web\jsonld\Event;

/* @var $this \luya\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
?>
<div class="row">
<?php
foreach($provider->models as $item):
    /* @var $item \johnnymcweed\event\models\Event */
    try {
        JsonLd::addGraph((new Event())
            ->setName($item->title)
            ->setDescription($item->teaser_text)
            ->setStartDate(Yii::$app->formatter->asDate($item->event_start))
            ->setEndDate(Yii::$app->formatter->asDate($item->event_end))
        );
    } catch (\luya\Exception $e) {
    } catch (\yii\base\InvalidConfigException $e) {
    }
    ?>
    <a href="<?= $item->eventUrl; ?>" class="col-md-3 field">
        <?php /* @var $item \johnnymcweed\event\models\Event */ ?>
        <h3><?= $item->title ?></h3>
        <span class="infobox"><?= Yii::$app->formatter->asDate($item->event_start) ?> - <?= Yii::$app->formatter->asDate($item->event_end) ?></span>
        <span><?= $item->teaser_text ?></span>
    </a>
<?php endforeach;?>
</div>

<?= LinkPager::widget(['pagination' => $provider->pagination]); ?>
```

#### views/event/default/category.php

```php
<?php
/* @var $this \luya\web\View */
/* @var $model \johnnymcweed\event\models\Cat */
?>
<h1><?= $model->title; ?></h1>
<?= $model->text ?>
<h2>Events:</h2>
<?php $events = $model->events;
foreach ($events as $event) { ?>
<a href="<?= $event->eventUrl; ?>">
<h3><?= $event->title ?></h3>
<?= $event->teaser_text ?>
</a>
<?php } ?>
<p><a href="<?= $route = \luya\helpers\Url::toRoute(['/event/categories']); ?>">Back</a></p>
```

#### views/event/default/event.php

```php
<?php
use yii\widgets\LinkPager;
/* @var $this \luya\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
?>
<div class="row">
    <?php
    foreach($provider->models as $item): ?>
        <a href="<?= $item->categoryUrl; ?>" class="col-md-3 field">
            <?php /* @var $item \johnnymcweed\event\models\Cat */ ?>
            <h3><?= $item->title ?></h3>
            <span><?= $item->teaser_text ?></span>
        </a>
    <?php endforeach;?>
</div>
<?= LinkPager::widget(['pagination' => $provider->pagination]); ?>
```
