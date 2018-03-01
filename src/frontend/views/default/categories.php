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