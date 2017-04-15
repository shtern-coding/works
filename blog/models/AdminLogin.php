<?php

namespace app\models;

use yii\base\Model;
use app\models\Admin;

class AdminLogin extends Model
{

    public $login;
    public $password;
    public $checkbox;

    public function rules()
    {
        return [
            [['login', 'password', 'checkbox'], 'required', 'message' => 'Поле должно быть заполнено'],
            ['password', 'PasswordValidate']
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'checkbox' => 'Запомнить меня'
        ];
    }

    public function getUser()
    {
        return Admin::findOne(['login' => $this->login]);
    }

    public function PasswordValidate($attribute)
    {
        if (!Admin::find()->where(['login' => $this->login])->andWhere([$attribute => md5($this->$attribute)])->one()) {
            $this->addError('login', 'Пароль или пользователь введены неверно');
            $this->addError($attribute, 'Пароль или пользователь введены неверно');
        }
    }
}