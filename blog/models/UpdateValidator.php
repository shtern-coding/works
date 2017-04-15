<?php

namespace app\components;

use yii\validators\Validator;

class UpdateValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $example = Articles::find()->where([$attribute => $this->$attribute])->andWhere(['!=', 'id', $this->id])->one();
        if ($example) {
            $this->addError($attribute, "Ошибка");
        }
    }
}