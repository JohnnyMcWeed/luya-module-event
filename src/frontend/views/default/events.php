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
            JsonLd::event()
                ->setName($item->title)
                ->setDescription($item->teaser_text)
                ->setStartDate(Yii::$app->formatter->asDate($item->event_start))
                ->setEndDate(Yii::$app->formatter->asDate($item->event_end));
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