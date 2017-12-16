<?php

namespace johnnymcweed\event\models;

use app\modules\event\admin\Module;
use luya\admin\traits\SoftDeleteTrait;
use luya\helpers\Inflector;
use luya\helpers\Url;
use Yii;
use luya\admin\ngrest\base\NgRestModel;

/**
 * Event Model.
 * 
 * @author Alexander Schmid <alex.schmid@stud.unibas.ch>
 * @since 1.0.0
 *
 * @property integer $id
 * @property text $title
 * @property text $text
 * @property text $teaser_text
 * @property integer $cat_id
 * @property integer $event_start
 * @property integer $event_end
 * @property text $url
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
class Event extends NgRestModel
{
    use SoftDeleteTrait;
    /**
     * @inheritdoc
     */
    public $i18n = ['title', 'text', 'teaser_text', 'image_list', 'file_list'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_event';
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-event-event';
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
            'event_start' => Module::t('event_start'),
            'event_end' => Module::t('event_end'),
            'url' => Module::t('url'),
            'cat_id' => Module::t('cat'),
            'image_id' => Module::t('image_id'),
            'image_list' => Module::t('image_list'),
            'file_list' => Module::t('file_list'),
            'timestamp_create' => Module::t('timestamp_create'),
            'timestamp_display_from' => Module::t('timestamp_display_from'),
            'timestamp_display_until' => Module::t('timestamp_display_until'),
            'is_display_limit' => Module::t('is_display_limit'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['title', 'text', 'teaser_text', 'url', 'image_list', 'file_list'], 'string'],
            [['cat_id', 'create_user_id', 'update_user_id', 'timestamp_create', 'timestamp_update',
                'timestamp_display_from', 'timestamp_display_until', 'event_start','event_end'], 'integer'],
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
            'cat_id' => ['selectModel', 'modelClass' => Cat::className(), 'valueField' => 'id', 'labelField' => 'title'],
            'event_start' => 'datetime',
            'event_end' => 'datetime',
            'url' => 'link',
            'image_id' => 'number',
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
            ['list', ['title', 'event_start', 'event_end', 'cat_id']],
            [['create', 'update'], ['title', 'text', 'teaser_text', 'cat_id', 'event_start', 'event_end', 'url',
                'image_id', 'image_list', 'file_list', 'timestamp_create', 'timestamp_display_from',
                'timestamp_display_until', 'is_display_limit']],
            ['delete', false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeGroups()
    {
        return [
            [['event_start', 'event_end', 'timestamp_create', 'timestamp_display_from', 'timestamp_display_until',
                'is_display_limit'], 'Time', 'collapsed'],
            [['image_id', 'image_list', 'file_list'], 'Media'],
        ];
    }

    public function ngRestFilters()
    {
        return [
            'Upcoming Events' => self::find()->where(['>=', 'event_start', time()]),
            'Past Events' => self::find()->where(['<=', 'event_end', time()]),
            'Current Events' => self::find()->where(['<=', 'event_start', time()])->andWhere(['>=', 'event_end', time()]),
        ];
    }

    /**
     *
     * @return string
     */
    public function getEventUrl()
    {
        return Url::toRoute(['/event/default/event', 'id' => $this->id, 'title' => Inflector::slug($this->title)]);
    }

    /**
     * Method to get available future events, which means, that the start date is after the time right now.
     *
     * @param bool $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAvailableFutureEvents($limit = false)
    {
        $q = self::find()
            ->andWhere('event_start >= :time', ['time' => time()])
            ->orderBy('event_start ASC');
        if ($limit) {
            $q->limit($limit);
        }
        $events = $q->all();
        // filter if display time is limited
        foreach ($events as $key => $event) {
            if ($event->is_display_limit) {
                if ($event->timestamp_display_until <= time()) {
                    unset($events[$key]);
                }
            }
        }
        return $events;
    }

    /**
     * Method to get past events, which means, that end date is before the time right now.
     *
     * @param bool $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPastEvents($limit = false)
    {
        $q = self::find()
            ->andWhere('event_end <= :time', ['time' => time()])
            ->orderBy('event_end DESC');
        if ($limit) {
            $q->limit($limit);
        }
        $events = $q->all();
        // filter if display time is limited
        foreach ($events as $key => $event) {
            if ($event->is_display_limit) {
                if ($event->timestamp_display_until <= time()) {
                    unset($events[$key]);
                }
            }
        }
        return $events;
    }
}