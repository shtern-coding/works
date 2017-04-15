<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Модель отвечающая за получение данных из формы создания 
 * и редактирования статьи в админке
 */
class CreateArticleForm extends Model
{
    public $id;
    public $name;
    public $url_name;
    public $date;
    public $article;
    public $tags;
    public $tagsArray;
    public $selectedTagsArray = [];
    
    /**
     * Правила валидации
     */
    public function rules() 
    {
        return [
            
            // Эти правила используются при обоих сценариях: create, update
            [['name', 'url_name', 'date', 'article'], 'required', 'message' => 'Поле должно быть заполнено', 'on' => ['create']],
            [['id', 'name', 'url_name', 'date', 'article'], 'required', 'message' => 'Поле должно быть заполнено', 'on' => ['update']],
            ['url_name', 'string', 'min' => 3, 'max' => 35, 'on' => ['create', 'update']],
            ['url_name', 'match', 'pattern' => '/[а-я]+/', 'not' => true, 'message'=> 'Нельзя использовать кириллические символы', 'on' => ['create', 'update']],
            ['url_name', 'match', 'pattern' => '/(--)/', 'not' => true, 'message'=> 'Нельзя использовать два дефиса подряд', 'on' => ['create', 'update']],
            ['tags', 'safe', 'on' => ['create', 'update']],

            // Это правило только для create
            ['url_name', 'unique', 'targetClass' => 'app\models\Articles', 'targetAttribute' => 'url_name', 'message' => 'Этот URL занят', 'on' => 'create'],

            // Это правило только для update
            ['url_name', 'UniqueUpdate', 'params' => ['message' => 'Этот URL занят'], 'on' => 'update']

        ];
    }
    
    /**
     * Присваивание полям Label-имен
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => 'Название',
            'url_name' => 'URL',
            'date' => 'Дата',
            'article' => 'Содержание',
            'tags' => 'Теги'
        ];
    }
    
    /**
     * Валидатор для проверки уникальности при редактировании. Пропускает нахождение строки, которая уже используется
     */
    public function UniqueUpdate($attribute, $params)
    {
        $exampleExist = Articles::find()->where([$attribute => $this->$attribute])->andWhere(['!=', 'id', $this->id])->one();
        if ($exampleExist) {
            $this->addError($attribute, $params['message']);
        }
    }

    /**
     * @return bool
     * Сохраняет новую статью
     */
    public function saveArticle()
    {
        $articlesTable = new Articles();
        $articlesTable->name = $this->name;
        $articlesTable->date = $this->date;
        $articlesTable->article = $this->article;
        $articlesTable->url_name = $this->url_name;
        $articlesTable->save();

        // сохраняем выбранные теги
        $articleId = Yii::$app->db->lastInsertID;
        $this->tagsToArticles($this->tags, $articleId);
    }

    /**
     * @return bool
     * Обновление уже загруженной статьи
     */
    public function updateArticle()
    {
        $updateLine = Articles::findOne($this->id);
        $updateLine->name = $this->name;
        $updateLine->url_name = $this->url_name;
        $updateLine->date = $this->date;
        $updateLine->article = $this->article;

        // удаляем старые связи тег-статья и загружаем новые
        $this->clearArticleTags($this->id);
        $this->tagsToArticles($this->tags, $this->id);

        // сохраняем статью
        $updateLine->save();
    }

    /**
     * @param $id
     * Загружаем статью выбранную для изменения в форму редактирования
     */
    public function loadUpdatedArticleInfo($id)
    {
        $updateLine = Articles::findOne($id);
        $this->id = $updateLine->id;
        $this->name = $updateLine->name;
        $this->url_name = $updateLine->url_name;
        $this->date = $updateLine->date;
        $this->article = $updateLine->article;
        $this->loadTags();
        $this->loadSelectedTags($id);
    }

    /*
     * Загружает список всех тегов
     */
    public function loadTags() {
        $tags = Tags::find()->all();
        $tagsArray = ArrayHelper::map($tags, 'id', 'tag');
        $this->tagsArray = $tagsArray;
    }

    /**
     * @param $id
     * Загружает список тегов, которые присвоены статье
     */
    public function loadSelectedTags($id)
    {
        $result = [];
        $tags = new ArticlesTags();
        $selectedTags = $tags->findAll(['article_id' => $id]);
        if (is_array($selectedTags)) {
            foreach ($selectedTags as $tag) {
                $result[$tag->tag_id] = ['Selected' => true];
            }
        }
        $this->selectedTagsArray = $result;
    }

    /**
     * @param $tag_ids
     * @param $article_id
     * Связывает id тегов с id статьи в промежуточной таблице
     */
    public function tagsToArticles($tag_ids, $article_id)
    {
        if (is_array($tag_ids) && count($tag_ids) > 0) {
            foreach ($tag_ids as $tag_id) {
                $articles_tags = new ArticlesTags();
                $articles_tags->article_id = $article_id;
                $articles_tags->tag_id = $tag_id;
                $articles_tags->save();
                unset($articles_tags);
            }
        }
    }

    /**
     * @param $id
     * Удаляет все привизанные теги выбранной статье
     */
    public function clearArticleTags($id) {
        ArticlesTags::deleteAll(['article_id' => $id]);
    }
}
