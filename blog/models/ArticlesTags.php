<?php

namespace app\models;

use yii\db\ActiveRecord;

class ArticlesTags extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%articles_tags}}';
    }
}
