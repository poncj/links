<?php

namespace app\models;

use yii\db\ActiveRecord;

class LinkClickLog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%link_click_log}}';
    }

    public function rules()
    {
        return [
            [['link_id', 'ip_address'], 'required'],
            [['link_id'], 'integer'],
            [['ip_address'], 'string', 'max' => 45],
        ];
    }

    public function getLink()
    {
        return $this->hasOne(Link::class, ['id' => 'link_id']);
    }
}
