<?php

namespace app\models;

use yii\db\ActiveRecord;

class Tags extends ActiveRecord
{

    /**
     * @return $this
     * Устанавливает связь
     */
    public function getArticles()
    {
        return $this->hasMany(Articles::className(), ['id' => 'article_id'])
            ->viaTable('{{%articles_tags}}', ['tag_id' => 'id']);
    }

}
