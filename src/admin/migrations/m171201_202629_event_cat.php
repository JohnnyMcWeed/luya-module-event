<?php

use yii\db\Migration;

/**
 * Class m171201_202629_event_cat
 *
 * Category migration class
 *
 * @author Alexander Schmid <alex.schmid@stud.unibas.ch>
 * @since 1.0.0
 */
class m171201_202629_event_cat extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('event_cat', [
            'id' => $this->primaryKey(),
            'title' => $this->string(150)->notNull(),
            'text' => $this->text(),
            'teaser_text' => $this->text(),
            'image_id' => $this->integer(11)->defaultValue(0),
            'image_list' => $this->text(),
            'file_list' => $this->text(),
            'create_user_id' => $this->integer(11)->defaultValue(0),
            'update_user_id' => $this->integer(11)->defaultValue(0),
            'timestamp_create' => $this->integer(11)->defaultValue(0),
            'timestamp_update' => $this->integer(11)->defaultValue(0),
            'timestamp_display_from' => $this->integer(11)->defaultValue(0),
            'timestamp_display_until' => $this->integer(11)->defaultValue(0),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'is_display_limit' => $this->boolean()->defaultValue(false),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('event_cat');
    }
}
