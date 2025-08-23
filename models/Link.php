<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель для таблицы "link"
 *
 * @property int $id
 * @property string $original_url
 * @property string $short_code
 * @property int $clicks
 * @property string $created_at
 */
class Link extends ActiveRecord
{
    public static function tableName()
    {
        return 'link';
    }

    public function rules()
    {
        return [
            [['original_url'], 'required'],
            [['original_url'], 'url', 'defaultScheme' => 'https', 'validSchemes' => ['http', 'https']], // требуем http/https
            [['original_url'], 'string', 'max' => 2048],
            [['short_code'], 'string', 'max' => 32],
            [['short_code'], 'unique'],
            [['clicks'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'original_url' => 'Original URL',
            'short_code'   => 'Short Code',
            'clicks'       => 'Clicks',
            'created_at'   => 'Created At',
        ];
    }

    public function beforeValidate()
    {
        if (empty($this->short_code)) {
            $this->short_code = $this->generateShortCode();
        }

        if (empty($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s');
        }

        return parent::beforeValidate();
    }

    private function generateShortCode(): string
    {
        $random = random_bytes(4); // 4 байта = 32 бита энтропии
        return $this->base62Encode($random);
    }

    private function base62Encode(string $data): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $value = intval(bin2hex($data), 16);
        $base = strlen($chars);

        $result = '';
        while ($value > 0) {
            $result = $chars[$value % $base] . $result;
            $value = intdiv($value, $base);
        }

        return $result ?: '0';
    }
}
