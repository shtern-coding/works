<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;


class SearchForm extends Model
{
    public $request;
    
    /**
     * Правила валидации
     */
    public function rules() 
    {
        return [
            ['request', 'string'],
        ];
    }
    
    /**
     * Присваивание полям Label-имен
     */
    public function attributeLabels()
    {
        return [
            'request' => '',
        ];
    }

    /**
     * @param $request Запрос
     * @return array|\yii\db\ActiveRecord[]
     * Выполняет поиск статей релеватных поисковому запросу выделяет запросы в тексте и возвращает всё
     */
    public function search($request) {
        $modelArticle = new Articles();
        $posts = $modelArticle->find()->orFilterWhere(['like', 'article', $request])
            ->orFilterWhere(['like', 'name', $request])
            ->all();
        if (preg_match('~[a-zA-Z]~iu',$request)) {
            $posts = $this->filterTags($posts, $request);
            foreach ($posts as $post) {
                $post->name = $this->markFinded($post->name, $request);
            }
        } else {
            foreach ($posts as $post) {
                $post->article = $this->markFinded($post->article, $request);
                $post->name = $this->markFinded($post->name, $request);
            }
        }
        return $posts;
    }

    /**
     * @param $string Строка (статья или заголовок)
     * @param $request Поисковый запрос
     * @return string
     * В найденных статьях находит слова конкретно совпадающие с поисковым запросом
     * и оборачивает их в span тег для их выделения на странице
     */
    private function markFinded($string, $request) {
        $pattern = "/($request)/iu";
        $changed = preg_replace_callback($pattern, function($matches) {
            return "<span class='underlined'>$matches[0]</span>";
        }, $string);
        return $changed;
    }

    /**
     * @param $string Строка (статья или заголовок)
     * @param $request Поисковый запрос
     * @return string
     * В найденных статьях находит сочетания букв на латинице, не являющиеся тегами и не находящиеся внутри тегов.
     * И вызывает метод markFind, выделяя в этих сочетаниях релеватные запросу символы
     */
    private function findEnglishWords($article, $request) {
        $pattern = '~(\s?[^/;&<>=\"\"\'\']*' . $request . '[^/;&<>=\"\"\'\']*\s)|(\s?[^/;&<>=\"\"\'\']*' . $request . '[^/;&<>=\"\"\'\']*)<~i';
        $changed = preg_replace_callback($pattern, function($matches) use($request) {
            return $this->markFinded($matches[0], $request);
        }, $article);
        return $changed;
    }

    /**
     * @param $posts Посты
     * @param $request Запрос
     * @return array
     * Проверяет найденные статьи на латинском на наличие в них сочетаний символов релевантных запросу,
     * но не являющихся html тегами, если статья не подходит, она удаляется из массива, если подходит
     * для неё вызывается метод findEnglishWords
     */
    private function filterTags($posts, $request) {
        foreach ($posts as $key => $post) {
            $finded = [];
//            $pattern = '~(\s[a-zA-Z0-9\(\),\.:]*' . $request . '[a-zA-Z0-9\(\),\.:]*\s)~iu';
            $pattern = '~(\s?[^/;&<>=\"\"\'\']*' . $request . '[^/;&<>=\"\"\'\']*\s)|(\s?[^/;&<>=\"\"\'\']*' . $request . '[^/;&<>=\"\"\'\']*)<~i';
            $match = preg_match_all($pattern, $post->article, $finded);
            if (!$match) {
                unset($posts[$key]);
            } else {
                $post->article = $this->findEnglishWords($post->article, $request);
            }
        }
        return array_values($posts);
    }
}
