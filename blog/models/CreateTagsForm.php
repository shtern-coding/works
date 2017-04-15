<?php

namespace app\models;

use yii\base\Model;
use app\models\Articles;
use app\models\Tags;
use app\models\ArticlesTags;

/**
 * Модель отвечающая за получение данных из формы создания 
 * и редактирования статьи в админке
 */
class CreateTagsForm extends Model
{
    public $tag;
    
    /**
     * Правила валидации
     */
    public function rules() 
    {
        return [

            [['tag'], 'required', 'message' => 'Поле должно быть заполнено'],

        ];
    }
    
    /**
     * Присваивание полям Label-имен
     */
    public function attributeLabels()
    {
        return [
            'tag' => 'Название'
        ];
    }

    /**
     * Проверяет существует ли уже такой тег, если нет, создает
     */
    public function addTag() {
        $query = Tags::findOne(['tag' => $this->tag]);
        if ($query) {
            return true;
        } else {
            $tags = new Tags();
            $tags->tag = mb_strtolower($this->tag);
            $tags->save();
        }
    }

    /**
     * Удаляет теги
     */
    public static function deleteTags($tags) {
        foreach ($tags as $tag) {
            $deleteTag = Tags::findOne($tag);
            $deleteTag->delete();
        }
    }


}
