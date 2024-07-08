<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%products}}';
    }

    public function rules()
    {
        return [
            ['name', 'string', 'max' => 100],
            ['price', 'number'],
            ['client_id', 'integer'],
            ['photo', 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Nome',
            'price' => 'Preço',
            'client_id' => 'Cliente',
            'photo' => 'Foto',
        ];
    }
}