<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\i18n\Formatter;

class Articles extends ActiveRecord
{
    public $dateFormated;
    public $tagsArray;

    /**
     * Метод автоматически выполняется после загрузки статей.
     * В данном случае форматируется дата
     */
    public function afterFind()
    {
        $format = new Formatter;
        $format->locale = "ru-RU";
        $this->dateFormated = $format->asDate($this->date, 'd MMMM Y');
    }

    /**
     * Связывание таблицы тегов со таблицей статей через промежуточную таблицу articles_tags
     */
    public function getTags()
    {
        return $this->hasMany(Tags::className(), ['id' => 'tag_id'])
            ->viaTable('{{%articles_tags}}', ['article_id' => 'id']);
    }

    /**
     * @param $articles Статьи
     * @param $offset Отбивка
     * @param $limit Сколько статей на странице
     * @return array
     * Из общего массива статей формирует другой, в зависимости от offset и limit.
     * Нужно для вывода статей относительно пагинации
     */
    public static function articlesCount($articles, $offset, $limit) {
        $result = [];
        $max = $offset+$limit-1;
        for ($i = $offset; $i <= $max; $i++) {
            if (isset($articles[$i])) {
                $result[] = $articles[$i];
            }
        }
        return $result;
    }

//    public function getSelectedArticles() {
//        return $this->getTags();
//    }
}