<?php

namespace app\models;

use yii\base\Model;

class Subscribe extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
        ];
    }
}