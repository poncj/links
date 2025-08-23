<?php

use yii\db\Migration;

class m250823_163356_create_link_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%link}}', [
            'id' => $this->primaryKey(),
            'original_url' => $this->string(2048)->notNull(),
            'short_code' => $this->string(16)->notNull()->unique(),
            'clicks' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%link}}');
    }
}
