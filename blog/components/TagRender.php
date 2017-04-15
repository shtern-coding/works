<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class TagRender extends Widget
{
    public $request;

    public function run()
    {
        return $this->tagRender();
    }

    /**
     * @return string
     * Формирует список тегов
     */
    public function tagRender()
    {
        $n = 1;
        $render = '';
        $count = count($this->request);
        foreach ($this->request as $tag) {
            $render .= '<a href='.Yii::$app->urlManager->createUrl(["site/tags", "request" => $tag->tag]).'>'.$tag->tag.'</a>';
            if ($n != $count) {
                $render .= '<span class="comma">, </span>';
            }
            $n++;
        }
        return $render;
    }

}