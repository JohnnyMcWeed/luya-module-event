<?php
/* @var $this \luya\web\View */
/* @var $model \johnnymcweed\event\models\Event */
?>
<h1><?= $model->title; ?></h1>
<span class="infobox"><?= Yii::$app->formatter->asDate($model->event_start) ?> - <?= Yii::$app->formatter->asDate($model->event_end) ?></span>
<?= $model->text ?>
<p><a href="<?= $route = \luya\helpers\Url::toRoute(['/events']); ?>">Back</a></p>