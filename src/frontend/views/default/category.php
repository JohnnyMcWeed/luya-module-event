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