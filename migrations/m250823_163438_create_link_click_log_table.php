<?php

use yii\db\Migration;

class m250823_163438_create_link_click_log_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%link_click_log}}', [
            'id' => $this->primaryKey(),
            'link_id' => $this->integer()->notNull(),
            'ip_address' => $this->string(45)->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // индекс для ускорения поиска по link_id
        $this->createIndex(
            'idx_link_click_log_link_id',
            '{{%link_click_log}}',
            'link_id'
        );
    }

    public function safeDown()
    {
        $this->dropIndex('idx_link_click_log_link_id', '{{%link_click_log}}');
        $this->dropTable('{{%link_click_log}}');
    }
}
