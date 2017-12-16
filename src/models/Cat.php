<?php

namespace johnnymcweed\event\models;

use app\modules\event\admin\Module;
use luya\helpers\Inflector;
use luya\helpers\Url;
use Yii;
use luya\admin\ngrest\base\NgRestModel;

/**
 * Category Model.
 *
 * @author Alexander Schmid <alex.schmid@stud.unibas.ch>
 * @since 1.0.0
 *
 * @property integer $id
 * @property string $title
 * @property text $text
 * @property text $teaser_text
 * @property integer $image_id
 * @property text $image_list
 * @property text $file_list
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property integer $timestamp_create
 * @property integer $timestamp_update
 * @property integer $timestamp_display_from
 * @property integer $timestamp_display_until
 * @property smallint $is_deleted
 * @property smallint $is_display_limit
 */
class Cat extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public $i18n = ['title', 'text', 'teaser_text', 'image_list', 'file_list'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_cat';
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-event-cat';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_INSERT, [$this, 'eventBeforeInsert']);
        $this->on(self::EVENT_BEFORE_UPDATE, [$this, 'eventBeforeUpdate']);
    }

    /**
     * @inheritdoc
     */
    public function eventBeforeUpdate()
    {
        $this->update_user_id = Yii::$app->adminuser->getId();
        $this->timestamp_update = time();
    }

    /**
     * @inheritdoc
     */
    public function eventBeforeInsert()
    {
        $this->create_user_id = Yii::$app->adminuser->getId();
        $this->update_user_id = Yii::$app->adminuser->getId();
        $this->timestamp_update = time();
        if (empty($this->timestamp_create)) {
            $this->timestamp_create = time();
        }
        if (empty($this->timestamp_display_from)) {
            $this->timestamp_display_from = time();
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Module::t('title'),
            'text' => Module::t('text'),
            'teaser_text' => Module::t('teaser_text'),
            'image_id' => Module::t('image_id'),
            'image_list' => Module::t('image_list'),
            'file_list' => Module::t('file_list'),
            'timestamp_create' => Module::t('timestamp_create'),
            'timestamp_display_from' => Module::t('timestamp_display_from'),
            'timestamp_display_until' => Module::t('timestamp_display_until'),
            'is_deleted' => Module::t('is_deleted'),
            'is_display_limit' => Module::t('is_display_limit'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'text', 'teaser_text', 'image_list', 'file_list'], 'string'],
            [['create_user_id', 'update_user_id', 'timestamp_create', 'timestamp_update',
                'timestamp_display_from', 'timestamp_display_until'], 'integer'],
            [['is_display_limit'], 'boolean'],
            [['image_id'], 'safe'],
            [['title'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function genericSearchFields()
    {
        return ['title', 'text', 'teaser_text', 'image_list', 'file_list'];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'title' => 'text',
            'text' => 'textarea',
            'teaser_text' => 'textarea',
            'image_id' => 'image',
            'image_list' => 'imageArray',
            'file_list' => 'fileArray',
            'timestamp_create' => 'datetime',
            'timestamp_display_from' => 'date',
            'timestamp_display_until' => 'date',
            'is_display_limit' => 'toggleStatus',
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['title', 'teaser_text','eventItemCount']],
            [['create', 'update'], ['title', 'text', 'teaser_text', 'image_id', 'image_list', 'file_list',
                'timestamp_create', 'timestamp_display_from', 'timestamp_display_until', 'is_display_limit']],
            ['delete', false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return ['eventItemCount'];
    }

    /**
     * @inheritdoc
     */
    public function ngrestExtraAttributeTypes()
    {
        return [
            'eventItemCount' => 'number',
        ];
    }

    /**
     * Returns the number of events in a category.
     *
     * @param $cat_id
     * @return int|string
     */
    public function getEventItemCount()
    {
        return Event::find()->andWhere(['cat_id' => $this->id])->count();
    }

    /**
     * Get events for this category.
     */
    public function getEvents()
    {
        return $this->hasMany(Event::class, ['cat_id' => 'id']);
    }

    /**
     * @return array
     */
    public function ngRestRelations()
    {
        return [
            ['label' => 'Events', 'apiEndpoint' => Event::ngRestApiEndpoint(), 'dataProvider' => $this->getEvents()],
        ];
    }

    /**
     *
     * @return string
     */
    public function getCategoryUrl()
    {
        return Url::toRoute(['/event/default/category', 'id' => $this->id, 'title' => Inflector::slug($this->title)]);
    }
}